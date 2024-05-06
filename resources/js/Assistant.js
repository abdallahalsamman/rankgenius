export default class Assistant {

  static get isInline() {
    return true;
  }

  get state() {
    return this._state;
  }

  constructor({ config, api }) {
    this.api = api;
    this.button = null;
    this.optionButtons = [];
    this._state = true;
    this.selectedText = null;
    this.range = null;
    this._settings = config;
    this.aiDrawer = document.getElementById('ai-drawer');


    this.CSS = {
      actions: 'change-case-action',
      toolbarLabel: 'change-case-toolbar__label',
      tool: 'change-case-tool',
      toolbarBtnActive: this.api.styles.settingsButtonActive,
      inlineButton: this.api.styles.inlineToolButton
    };

    this.caseOptions = {
      'shorten': 'Shorten',
      'expand': 'Expand',
      'suggest': 'Suggestions',
      'prompt': 'Prompt AI'
    }
  }

  set state(state) {
    this._state = state;
    this.button.classList.toggle(this.CSS.toolbarBtnActive, state);
  }

  get title() {
    return 'AI Assistant';
  }

  render() {
    const currBlockIdx = this.api.blocks.getCurrentBlockIndex();
    const block = this.api.blocks.getBlockByIndex(currBlockIdx);
    if (block.name == "list") return;

    this.button = document.createElement('button');
    this.button.type = 'button';
    this.button.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg>
    `;
    this.button.classList.add(this.CSS.inlineButton);

    return this.button;
  }

  checkState(selection) {
    const text = selection.anchorNode;
    if (!text) return;
  }

  extractAllText() {
    let blockCount = this.api.blocks.getBlocksCount();
    const html = [];
    for (let blockIdx = 0; blockIdx < blockCount; blockIdx++) {
      const block = this.api.blocks.getBlockByIndex(blockIdx);
    
      let text = block.holder.textContent;
      if (!text) continue;

      let container = block.holder.children[0].children[0].cloneNode(true);
      const removeAllAttributes = (node) => {
        Array.from(node.attributes).forEach(attr => node.removeAttribute(attr.name));
        Array.from(node.children).forEach(child => removeAllAttributes(child));
      };
      removeAllAttributes(container);
      
      if (block.name === "paragraph") {
        let newContainer = document.createElement("p");
        newContainer.innerHTML = container.innerHTML;
        container = newContainer
      }
      
      let blockHTML = container.outerHTML;

      html.push(blockHTML);
    }

    return html.join('\n\n');
  }

  handleClick(range, option) {
    if (!range) return

    const clone = range.cloneContents();
    if (!clone) return

    clone.childNodes.forEach(node => {
      if (node.nodeName !== '#text') return;
    });
    
    if (option == 'prompt') {
      option = prompt('Enter your prompt');
      if (!option) return;
    }

    Livewire.dispatch('showDrawer');
    this.aiDrawer.innerHTML = '<div class="flex min-h-screen justify-center items-center"><span class="loading loading-spinner loading-lg"></span></div>';

    this.getCompletions(option)
    .then(data => {
      if (option == 'shorten') {
        range.deleteContents();
        range.insertNode(document.createTextNode(data['suggestions'][0]));
      }

      if (option == 'expand') {
        range.collapse(false);
        range.insertNode(document.createTextNode(' ' + data['suggestions'][0]));
      }

      if (option == 'expand' || option == 'shorten') {
        Livewire.dispatch('hideDrawer');
        return;
      }

      this.aiDrawer.innerHTML = ''; // Clear previous suggestions if any
      data['suggestions'].forEach((suggestion) => {
        const suggestionElement = document.createElement('div');
        suggestionElement.textContent = suggestion;
        suggestionElement.classList.add('cursor-pointer', 'p-2', 'hover:bg-gray-100', 'mb-2', 'mx-2', 'border', 'border-gray-200', 'rounded', 'text-lg');
        suggestionElement.onclick = () => {
          range.deleteContents();
          range.insertNode(document.createTextNode(suggestion));
          Livewire.dispatch('hideDrawer');
          this.aiDrawer.innerHTML = ''; // Clear suggestions after selection
        };
        this.aiDrawer.appendChild(suggestionElement);
      });
    })
    this.api.inlineToolbar.close();
  }

  getCompletions(option) {
    return new Promise((resolve, reject) => {
      const text = this.selectedText.textContent;
      const url = this._settings.assistantUrl;

      const data = {
        articleHTML: this.extractAllText(),
        selectedText: text,
        prompt: option
      }

      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          "X-CSRF-Token": this._settings.csrfToken
        },
        body: JSON.stringify(data)
      })
        .then(response => response.json())
        .then(data => resolve(data))
        .catch(error => reject(error));
    });
  }

  surround(range) {
    this.selectedText = range.cloneContents();
    this.actions.hidden = !this.actions.hidden;
    this.range = !this.actions.hidden ? range : null;
    this.state = !this.actions.hidden;
  }

  renderActions() {
    this.actions = document.createElement('div');
    this.actions.classList.add(this.CSS.actions);
    const actionsToolbar = document.createElement('div');
    actionsToolbar.classList.add(this.CSS.toolbarLabel);
    actionsToolbar.innerHTML = this.title;

    this.actions.appendChild(actionsToolbar);

    this.optionButtons = Object.keys(this.caseOptions).map(option => {
      const btnOption = document.createElement('div');
      btnOption.classList.add(this.CSS.tool);
      btnOption.dataset.mode = option;
      btnOption.innerHTML = this.caseOptions[option];
      return btnOption
    })

    for (const btnOption of this.optionButtons) {
      this.actions.appendChild(btnOption);
      this.api.listeners.on(btnOption, 'click', () => {
        this.handleClick(this.range, btnOption.dataset.mode)
      });
    }

    this.actions.hidden = true;
    return this.actions;
  }

  destroy() {
    for (const btnOption of this.optionButtons) {
      this.api.listeners.off(btnOption, 'click');
    }
  }
}
