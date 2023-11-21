<div>

    {{-- nav --}}
    <div class="border-b-[1px]">
        <div
            class="mx-auto grid max-w-[1024px] grid-cols-[_1fr_4fr_1.5fr] items-center p-4">
            <a href="/">
                <div class="flex items-center justify-between">
                    <div class="mr-5">
                        <x-icon class="h-[30px] w-[30px]"
                            name="iconsax.bul-ranking" />
                    </div>
                    <p class="text-[1.25rem] font-bold">ContentAIO</p>
                </div>
            </a>
            <div class="flex h-fit justify-center gap-[2rem] font-semibold">
                <a href="/#autoblog">AutoBlog</a>
                <a href="/#pricing">Pricing</a>
                <a href="/#faq">FAQ</a>
                <a href="/learn">Learn</a>
            </div>
            <div class="flex justify-between">
                <x-button class="btn-primary btn-sm text-white" external
                    label="Get 3 Free Articles"
                    link="{{ route('dashboard') }}" />
                <x-button
                    class="btn-sm border-transparent bg-transparent text-primary"
                    external label="Login" link="{{ route('login') }}" />
            </div>
        </div>
    </div>

    {{-- hero section --}}
    <div class="w-full">
        <div class="mx-auto max-w-[768px] px-4 pb-8 pt-14">
            <div class="flex flex-col gap-4">
                <h1
                    class="break-words px-4 text-center text-5xl font-bold leading-normal">
                    Generate <mark
                        class="bg-transparent text-[#4f00ff]">high-quality
                        articles</mark> that
                    <mark
                        class="bg-transparent text-[#4f00ff]">auto-publish</mark>
                    to your blog
                </h1>
                <p class="text-center text-2xl">Instantly get hundreds of
                    relevant articles that are unique
                    and optimized for your niche.</p>
            </div>
            <div class="mt-10 w-full">
                <form>
                    <div
                        class="mx-8 flex flex-row gap-2 rounded-lg border-[0.8px] bg-[#F7FAFC] p-5 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                        <div class="flex-grow" role="group">
                            <x-input class="text-xl"
                                placeholder="your@email.com" type="email" />
                        </div>
                        <div>
                            <x-button class="btn-primary text-lg text-white"
                                icon-right="fas.arrow-right"
                                label="Get 3 Free Articles" type="submit" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="mx-auto max-w-[1024px] px-4">
            <div class="mb-10 flex justify-center gap-10">
                <div
                    class="flex items-center gap-2 rounded-lg px-3 py-1 font-medium">
                    <x-icon class="h-7 w-7 text-green-600"
                        name="bi.check-all" />
                    <p>SEO optimized</p>
                </div>
                <div
                    class="flex items-center gap-2 rounded-lg px-3 py-1 font-medium">
                    <x-icon class="h-7 w-7 text-green-600"
                        name="bi.check-all" />
                    <p><span>Plagiarism Free</span></p>
                </div>
                <div
                    class="flex items-center gap-2 rounded-lg px-3 py-1 font-medium">
                    <x-icon class="h-7 w-7 text-green-600"
                        name="bi.check-all" />
                    <p>No credit card required</p>
                </div>
                <div
                    class="flex items-center gap-2 rounded-lg px-3 py-1 font-medium">
                    <x-icon class="h-7 w-7 text-green-600"
                        name="bi.check-all" />
                    <p>Articles in 30 seconds</p>
                </div>
            </div>
        </div>
    </div>

    {{-- slider --}}
    <div class="bg-gradient-to-t from-white to-[#F7FAFC] p-10">
        <div>
            <div class="mb-10 text-center text-lg">
                <p>Our AI is trained on content from leading publishers</p>
            </div>
        </div>
        <div
            class="relative m-auto w-full overflow-hidden bg-white before:absolute before:left-0 before:top-0 before:z-[2] before:h-full before:w-[100px] before:bg-[linear-gradient(to_right,white_0%,rgba(255,255,255,0)_100%)] before:content-[''] after:absolute after:right-0 after:top-0 after:z-[2] after:h-full after:w-[100px] after:-scale-x-100 after:bg-[linear-gradient(to_right,white_0%,rgba(255,255,255,0)_100%)] after:content-['']">
            <div class="flex animate-infinite-slider">
                <div
                    class="slide flex w-full items-center justify-center gap-20">
                    @foreach (array_merge(...array_fill(0, 5, ['abc', 'bbc', 'cbc', 'cnn', 'globe-mail', 'national-post', 'quora', 'wikipedia'])) as $logo)
                        <img alt="ABC News Logo" class="h-8"
                            src="{{ asset('resources/images/' . $logo . '.png') }}" />
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- feature --}}
    <div class="mx-auto max-w-[1024px] px-4 py-20">
        <div id="features">
            <div class="text-center">
                <h2 class="text-4xl font-bold">Feature-Rich Articles that Bring
                    Traffic</h2>
                <p class="mt-4 text-xl">Journalist AI crafts content that is
                    well-structured, appropriate
                    for your business and that ranks on Google.</p>
            </div>
            <div class="mt-16">
                <div class="flex flex-col gap-8">
                    <div
                        class="rounded-lg border-[0.8px] p-4 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                        <div class="flex items-center gap-4">
                            <x-icon class="h-6 w-6" name="bi.star-fill" />
                            <h3 class="text-xl font-bold">Human expert-level
                                content</h3>
                        </div>
                        <p class="mt-4">Journalist AI is equivalent to an
                            expert writer,
                            writing
                            informed articles that make sense for your business.
                        </p>
                    </div>
                    <div
                        class="rounded-lg border-[0.8px] p-4 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                        <div class="flex items-center gap-4">
                            <x-icon class="h-6 w-6" name="fas.copyright" />
                            <h3 class="text-xl font-bold">Copyright-free Images
                                &amp; Videos</h3>
                        </div>
                        <p class="mt-4">All articles include a featured-image
                            and in-article
                            images in relevant paragraphs. These images are
                            copyright free. You can also choose to include
                            Youtube
                            videos.</p>
                    </div>
                    <div
                        class="rounded-lg border-[0.8px] p-4 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                        <div class="flex items-center gap-4">
                            <x-icon class="h-6 w-6" name="bi.file-code-fill" />
                            <h3 class="text-xl font-bold">Extensive Formatting
                            </h3>
                        </div>
                        <p class="mt-4">Articles look good and are properly
                            formatted,
                            containing
                            all important HTML elements such as h2s, h3s,
                            paragraphs, lists and tables.</p>
                    </div>
                    <div
                        class="rounded-lg border-[0.8px] p-4 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                        <div class="flex items-center gap-4">
                            <x-icon class="h-6 w-6" name="bi.list-nested" />
                            <h3 class="text-xl font-bold">Table of Contents</h3>
                        </div>
                        <p class="mt-4">All articles include a thoughtful
                            outline that makes
                            sense from beginning to end. Content follows a
                            natural
                            flow to engage the reader and maximize read time.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="mx-auto max-w-[1024px] px-4 py-8">
        <div class="flex items-center justify-center">
            <a class="btn-primary rounded-lg px-20 py-5 text-2xl font-semibold text-white"
                href="{{ route('dashboard') }}">Get 3 Free Articles Today</a>
        </div>
    </div>

    {{-- generation --}}
    <div class="mx-auto max-w-[1024px] px-4 py-20">
        <div id="features">
            <div class="text-center">
                <h2 class="text-4xl font-bold">Customized Bulk-Generation</h2>
                <p class="mt-4 text-xl">Mass-generate articles according to your
                    preferences - niche, keywords, titles, structure &amp;
                    headings and more.</p>
            </div>
            <div
                class="mt-16 grid auto-rows-min grid-cols-[_1.6fr_1fr] gap-x-1 gap-y-14">
                <div
                    class="rounded-lg border-[0.8px] p-4 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                    <h3 class="text-xl font-bold">Tell us Keywords, Titles or
                        your Business.</h3>
                    <p class="mt-4">Generate articles based on your own
                        keywords, or just tell Journalist AI about your business
                        &amp; niche.</p>
                    <p class="mt-4">Alternatively, you can input specific
                        titles for the articles. Every article will be totally
                        unique.</p>
                </div>
                <div
                    class="h-52 overflow-hidden rounded-lg border-2 border-dashed border-[#ceb8ff] p-2 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                    <img alt="modes" loading="lazy"
                        src="https://tryjournalist.com/_next/image?url=%2Fmodes.png&w=1920&q=75">
                </div>
                <div
                    class="rounded-lg border-[0.8px] p-4 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                    <h3 class="text-xl font-bold">Customize Outline &amp;
                        Structure</h3>
                    <p class="mt-4">Take full control of the headings of your
                        articles. Add custom sections, such as Introductions,
                        FAQ, Opinions, etc.</p>
                    <p class="mt-4">You can let some of the headings be
                        consistent and the rest generated. You can also add a
                        CTA at the end of each article.</p>
                </div>
                <div
                    class="h-52 overflow-hidden rounded-lg border-2 border-dashed border-[#ceb8ff] p-2 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                    <img alt="modes" loading="lazy"
                        src="https://tryjournalist.com/_next/image?url=%2Fmodes.png&w=1920&q=75">
                </div>
                <div
                    class="rounded-lg border-[0.8px] p-4 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                    <h3 class="text-xl font-bold">Language &amp; Personality
                    </h3>
                    <p class="mt-4">Choose from 150+ languages. Choose how
                        creative your articles can be, from<i>
                            Correct/Factual</i> all the way to
                        <i>Creative/Original</i>. Apply a specific tonality,
                        such as<i> informal</i> or <i>persuasive</i>.
                    </p>
                </div>
                <div
                    class="h-52 overflow-hidden rounded-lg border-2 border-dashed border-[#ceb8ff] p-2 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                    <img alt="modes" loading="lazy"
                        src="https://tryjournalist.com/_next/image?url=%2Fmodes.png&w=1920&q=75">
                </div>
                <div
                    class="rounded-lg border-[0.8px] p-4 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                    <h3 class="text-xl font-bold">Generate &amp; Publish In
                        Mass</h3>
                    <p class="mt-4">Choose how many articles should be
                        generated, based on previous preferences. You can either
                        download them as a <i>zip</i> file or publish in
                        one-click to your website.</p>
                    <p class="mt-4">You can also let Journalist AI schedule,
                        generate and publish for you automatically with an
                        <i>AutoBlog</i>.
                    </p>
                </div>
                <div
                    class="h-52 overflow-hidden rounded-lg border-2 border-dashed border-[#ceb8ff] p-2 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                    <img alt="modes" loading="lazy"
                        src="https://tryjournalist.com/_next/image?url=%2Fmodes.png&w=1920&q=75">
                </div>
            </div>
        </div>
    </div>

    {{-- automate with autoblog --}}
    <div class="mx-auto max-w-[1024px] px-4 py-20">
        <div id="autoblog">
            <div class="text-center">
                <h2 class="text-4xl font-bold">Automate with <mark
                        class="bg-transparent text-[#4f00ff]">AutoBlog</mark>
                </h2>
                <p class="mt-4 text-xl">All the features you need to drive
                    traffic to your blog and run
                    it on auto-pilot.</p>
            </div>
            <div class="mt-16 grid grid-cols-2 gap-12">
                <div
                    class="rounded-lg border-[0.8px] shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                    <div class="px-5">
                        <img alt="autoblog" loading="lazy"
                            src="https://tryjournalist.com/_next/image?url=%2Fautoblog.png&w=1920&q=75" />
                    </div>
                    <div class="p-4 border-t-[0.8px]">
                        <h3 class="text-xl font-bold">Automatic Posting</h3>
                        <p class="mt-4">AutoBlog allows you to set a schedule to generate
                            &amp; publish articles. Run your blog on auto-pilot,
                            making Journalist AI your full-time employee.</p>
                    </div>
                </div>
                <div
                    class="rounded-lg border-[0.8px] shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                    <div class="px-5">
                        <img alt="integrations" loading="lazy"
                            src="https://tryjournalist.com/_next/image?url=%2Fautoblog.png&w=1920&q=75" />
                    </div>
                    <div class="p-4 border-t-[0.8px]">
                        <h3 class="text-xl font-bold">Integrate with any Website</h3>
                        <p class="mt-4">It's easy to integrate with your website. We also
                            have an integration with Zapier, allowing you to
                            send the articles to any platform.</p>
                    </div>
                </div>
                <div
                    class="rounded-lg border-[0.8px] shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                    <div class="px-5">
                        <img alt="interlinking" loading="lazy"
                            src="https://tryjournalist.com/_next/image?url=%2Fautoblog.png&w=1920&q=75" />
                    </div>
                    <div class="p-4 border-t-[0.8px]">
                        <h3 class="text-xl font-bold">Internal Linking</h3>
                        <p class="mt-4">Journalist AI analyzes your website structure and
                            intelligently places links in your articles.
                            Articles will be semantically analyzed and linked.
                            The end result looks natural and boosts your SEO
                            efforts.</p>
                    </div>
                </div>
                <div
                    class="rounded-lg border-[0.8px] shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                    <div class="px-5">
                        <img alt="external linking" loading="lazy"
                            src="https://tryjournalist.com/_next/image?url=%2Fautoblog.png&w=1920&q=75" />
                    </div>
                    <div class="p-4 border-t-[0.8px]">
                        <h3 class="text-xl font-bold">Real-Time External Linking</h3>
                        <p class="mt-4">Jornalist AI will analyze the latest news and
                            relevant sources for information that fits the
                            generated article. It will carefully place links
                            according to context and keywords. The end result
                            feels like up-to-date content that is seen in a
                            good-eye by Google.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

        {{-- CTA --}}
        <div class="mx-auto max-w-[1024px] px-4 py-8">
            <div class="flex items-center justify-center">
                <a class="btn-primary rounded-lg px-20 py-5 text-2xl font-semibold text-white"
                    href="{{ route('dashboard') }}">Get 3 Free Articles Today</a>
            </div>
        </div>


</div>
