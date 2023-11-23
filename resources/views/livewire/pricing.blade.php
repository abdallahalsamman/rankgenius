<section>
    <div class="mx-auto flex max-w-[1024px] flex-col gap-14 px-4 py-20">
        <div id="pricing">
            <div class="text-center">
                <h2 class="text-4xl font-bold">Pricing</h2>
                <p class="mt-4 text-xl">Stop over-paying for quality. We have the
                    most competitive prices in the market.</p>
            </div>
        </div>

        <div class="flex flex-col gap-14">
            <div class="flex justify-center gap-2">
                <p>Monthly</p>
                <x-custom-toggle :enabled="$yearly" class="checked:bg-neutral-900"
                    wire:model.change="yearly" />
                <div class="flex gap-2">
                    <p>Yearly</p>
                    <div>
                        <span
                            class="rounded-lg border-[0.8px] bg-white px-3 py-1 font-semibold shadow-sm">Save
                            up to
                            50%</span>
                    </div>
                </div>
            </div>


            <div class="grid auto-cols-min grid-cols-3 gap-4 leading-loose">
                <div
                    class="flex flex-col rounded-lg border-2 border-gray-200 bg-white p-8 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                    <p class="text-lg font-bold">Writer</p>
                    <p class="text-4xl font-bold">
                        ${{ $pricingTable['writer']['price'][$yearly ? 'yearly' : 'monthly'] }}
                    </p>
                    <p><x-icon class="mr-1 h-5 w-5"
                            name="phosphor.coins-light" /><b>{{ $pricingTable['writer']['price']['credits'] }}</b>
                        credits per month</p>
                    <hr class="my-5" />

                    <div class="flex flex-col gap-1">
                        @foreach ($pricingTable['writer']['features'] as $feature)
                            <div class="flex items-center">
                                <x-icon class="mr-2 h-5 w-5"
                                    name="bi.check-circle-fill" />
                                <span
                                    class="text-gray-700">{!! $feature['html'] !!}</span>

                                @if (isset($feature['icon']))
                                    <x-icon :name="$feature['icon']"
                                        class="ml-2 h-5 w-5" />
                                @endif

                                @if (isset($feature['hint']))
                                @endif
                            </div>
                        @endforeach

                        @foreach (array_merge($pricingTable['autoblog']['features'], $pricingTable['ultimate']['features']) as $feature)
                            <div class="flex items-center">
                                <x-icon class="mr-2 h-5 w-5" name="bi.x" />
                                <span
                                    class="text-gray-700">{!! $feature['html'] !!}</span>

                                @if (isset($feature['icon']))
                                    <x-icon :name="$feature['icon']"
                                        class="ml-2 h-5 w-5" />
                                @endif

                                @if (isset($feature['hint']))
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-auto">
                        <x-button
                            class="w-full border-2 border-black bg-neutral-900 text-white shadow-lg hover:border-black hover:bg-white hover:text-neutral-900"
                            label="Get
                            Started" />
                    </div>
                </div>
                <div
                    class="relative flex flex-col rounded-lg border-2 border-black bg-white p-8 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                    <span
                        class="absolute left-1/2 top-0 -translate-x-1/2 -translate-y-1/2 transform rounded-full bg-neutral-900 px-5 py-2 text-sm font-semibold text-white">Most
                        Popular</span>

                    <p class="text-lg font-bold">AutoBlog</p>
                    <p class="text-4xl font-bold">
                        ${{ $pricingTable['autoblog']['price'][$yearly ? 'yearly' : 'monthly'] }}
                    </p>
                    <p><x-icon class="mr-1 h-5 w-5"
                            name="phosphor.coins-light" /><b>{{ $pricingTable['autoblog']['price']['credits'] }}</b>
                        credits per month</p>
                    <hr class="my-5" />

                    <div class="flex flex-col gap-1">
                        @foreach (array_merge($pricingTable['writer']['features'], $pricingTable['autoblog']['features']) as $feature)
                            <div class="flex items-center">
                                <x-icon class="mr-2 h-5 w-5"
                                    name="bi.check-circle-fill" />
                                <span
                                    class="text-gray-700">{!! $feature['html'] !!}</span>

                                @if (isset($feature['icon']))
                                    <x-icon :name="$feature['icon']"
                                        class="ml-2 h-5 w-5" />
                                @endif

                                @if (isset($feature['hint']))
                                @endif
                            </div>
                        @endforeach

                        @foreach ($pricingTable['ultimate']['features'] as $feature)
                            <div class="flex items-center">
                                <x-icon class="mr-2 h-5 w-5" name="bi.x" />
                                <span
                                    class="text-gray-700">{!! $feature['html'] !!}</span>

                                @if (isset($feature['icon']))
                                    <x-icon :name="$feature['icon']"
                                        class="ml-2 h-5 w-5" />
                                @endif

                                @if (isset($feature['hint']))
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-auto">
                        <x-button
                            class="w-full border-2 border-black bg-neutral-900 text-white shadow-lg hover:border-black hover:bg-white hover:text-neutral-900"
                            label="Get
                                Started" />
                    </div>

                </div>
                <div
                    class="rounded-lg border-2 border-gray-200 bg-white p-8 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                    <p class="text-lg font-bold">Ultimate</p>
                    <p class="text-4xl font-bold">
                        ${{ $pricingTable['ultimate']['price'][$customCredits][$yearly ? 'yearly' : 'monthly'] }}
                    </p>
                    <p><x-icon class="mr-1 h-5 w-5"
                            name="phosphor.coins-light" /><b>{{ $pricingTable['ultimate']['price'][$customCredits]['credits'] }}</b>
                        credits per month</p>
                    <hr class="my-5" />

                    <div class="flex flex-col gap-1">
                        @foreach (array_merge($pricingTable['writer']['features'], $pricingTable['autoblog']['features'], $pricingTable['ultimate']['features']) as $feature)
                            <div class="flex items-center">
                                <x-icon class="mr-2 h-5 w-5"
                                    name="bi.check-circle-fill" />
                                <span
                                    class="text-gray-700">{!! $feature['html'] !!}</span>

                                @if (isset($feature['icon']))
                                    <x-icon :name="$feature['icon']"
                                        class="ml-2 h-5 w-5" />
                                @endif

                                @if (isset($feature['hint']))
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <hr class="my-3" />
                    <div class="flex flex-col gap-1">
                        <div class="font-semibold">INCREASE YOUR CREDITS</div>
                        <div class="flex justify-between text-xs">
                            @foreach ($pricingTable['ultimate']['price'] as $key => $value)
                                @php
                                    $check = $customCredits != 0 && $customCredits != count($pricingTable['ultimate']['price']) - 1 && $customCredits == $key;
                                @endphp
                                <div
                                    class="{{ $check ? 'font-semibold text-white bg-neutral-900 px-1 rounded-md' : '' }} h-[1rem]">
                                    {{ $check ? $value['credits'] : '' }}</div>
                            @endforeach
                        </div><input class="range range-xs checked:bg-neutral-900"
                            max="8" min="0" type="range"
                            wire:model.live="customCredits" />
                        <div class="mb-3 flex justify-between text-xs">
                            <span>300</span>
                            <span>25000</span>
                        </div>
                    </div>


                    <div class="mt-auto">
                        <x-button
                            class="w-full border-2 border-neutral-900 bg-neutral-900 text-white shadow-lg hover:border-neutral-900 hover:bg-white hover:text-neutral-900"
                            label="Get
                            Started" />
                    </div>
                </div>

                <div
                    class="col-span-3 mx-auto w-full max-w-xl rounded-lg border-2 border-gray-200 bg-white p-8 shadow-[2px_2px_20px_10px_rgba(0,0,0,0.05)]">
                    <div>
                        <div
                            class="w-full rounded-lg border-[1px] border-neutral-900 bg-[#FAFAFA] py-3 text-center">
                            <h3 class="text-xl font-semibold">Pay as you go</h3>
                        </div>
                        <div class="py-4">
                            <div class="flex items-center">
                                <x-icon class="mr-2 h-5 w-5"
                                    name="bi.check-circle-fill" />
                                <p>Purchase one-time for a higher rate
                                    (<span>$0.80</span>)
                                </p>
                            </div>
                            <div class="flex items-center">
                                <x-icon class="mr-2 h-5 w-5"
                                    name="bi.check-circle-fill" />
                                <p>Includes all features in <b>Writer + Internal
                                        &amp; External Linking.</b>
                                </p>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <x-button
                                class="w-full border-2 border-neutral-900 bg-neutral-900 text-white shadow-lg hover:border-neutral-900 hover:bg-white hover:text-neutral-900"
                                label="Get
                                Started" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex mx-auto px-8 py-2 items-center bg-white border-2 rounded-full shadow-md font-medium text-lg gap-x-2">
                <x-icon class="h-5 w-5" name="phosphor.coins-light" />
                <p class="">1 credit</p>
                <p class=""> = </p>
                <p class="">1 article</p>
            </div>

        </div>

        <div class="p-4 text-center text-[#919191]">
            <p>Have any special requests? <b>Get in touch</b> for a quote.</p>
        </div>

    </div>
</section>
