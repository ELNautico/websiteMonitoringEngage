<x-layout>
    <h1 class="text-5xl font-extrabold text-center text-white mt-8 mb-12">Website Monitoring</h1>
    <div class="m-auto max-w-5xl">
        <div class="mt-12 pb-8">
            <section class="p-6 bg-gray-700 mr-16 ml-16 rounded-xl align-center">
                <form method="POST" action="/url">
                    <!-- Cross-site request forgeries -->
                    @csrf
                    <div class="relative">
                        <label
                            for="url"
                            class="block mb-2 uppercase font-bold text-xs text-white"
                        >
                            New URL <span class="text-red-500"> * </span>
                        </label>
                        <input
                            class="block w-full p-4 max-w-4xl text-sm text-gray-900 rounded-lg bg-gray-800 mb-3"
                            type="text"
                            name="url"
                            id="url"
                            placeholder="Add new URL to monitor... (with https://)"
                            required
                        >
                        @error('url')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                        <label
                            for="searchQ"
                            class="block mb-2 uppercase font-bold text-xs text-white"
                        >
                            HTML/Text on website
                        </label>
                        <input
                            class="block w-full p-4 max-w-4xl text-sm text-gray-900 rounded-lg bg-gray-800 mb-3"
                            type="text"
                            name="searchQ"
                            id="searchQ"
                            placeholder="HTML/Text..."
                        >
                        @error('searchQ')
                            <p class="text-red-500 text-xs mt-2">The "HTML/Text" field is required</p>
                        @enderror

                        <button
                            type="submit"
                            class="text-white font-medium rounded-lg text-sm mt-2 px-8 py-3 bg-fuchsia-600 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            Add
                        </button>
                    </div>
                </form>
            </section>
        </div>

        <div class="overflow-x-auto relative shadow-md rounded-lg">
            <table class="mt-5 w-full text-sm text-left text-gray-500">
                <thead class="rounded-lg text-sm text-white uppercase bg-fuchsia-600">
                    <tr>
                        <th scope="col" class="py-4 pl-6">
                            Website
                        </th>
                        <th scope="col" class="py-4 px-6">
                            Last Checked
                        </th>
                        <th scope="col" class="py-4 px-10">
                            Response-Time
                        </th>
                        <th scope="col" class="py-4 px-6">
                            Active&#160;&#160;&#160;HTML
                        </th>
                        <th scope="col" class="py-4 pl-6"></th>
                        <th scope="col" class="py-4 pr-6"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($urls as $url)
                    <!-- After every URL a border BUT the last -->
                    @if($url !== $urls->last())
                    <tr class="bg-gray-700 border-b">
                    @else
                    <tr class="bg-gray-700">
                    @endif
                        <th scope="row" class="py-4 pl-5 font-medium text-white whitespace-nowrap">
                            <a href="{{ $url->url }}" target="_blank">
                                <!-- Don't display the first 8 characters, which are https:// -->
                                {{ substr($url->url, 8)}}
                            </a>
                        </th>
                        <td class="py-4 px-6 text-sm text-gray-400">
                            {{ $url->updated_at->diffForHumans() }}
                        </td>
                        @if($url->requestTime >= 4.9)
                            <td class="py-4 px-10 text-red-500 font-bold">
                                <div class="bg-zinc-500 w-12/12">
                                    <div class="bg-red-600 text-right py-3 px-1 text-white"></div>
                                    <div class="text-center">
                                        > {{ $url->requestTime }} sec
                                    </div>
                                </div>
                            </td>
                        @else
                            <td class="py-4 px-10 text-gray-400">
                                <div class="bg-zinc-500 w-max">
                                    <div @class([
                                // Set color depending on how long the response time is
                                'bg-green-400' =>  $url->requestTime <= 2,
                                'bg-yellow-400' => $url->requestTime >= 2.01 && $url->requestTime <= 3.5,
                                'bg-amber-600' => $url->requestTime >= 3.51 && $url->requestTime <= 4.9,
                                'bg-red-600' => $url->requestTime >= 4.91,
                                // Set width depending on how long the response time is
                                'w-1/12' =>  $url->requestTime <= 0.1,
                                'w-2/12' => $url->requestTime >= 0.11 && $url->requestTime <= 0.2,
                                'w-3/12' => $url->requestTime >= 0.21 && $url->requestTime <= 0.3,
                                'w-4/12' => $url->requestTime >= 0.31 && $url->requestTime <= 0.5,
                                'w-5/12' => $url->requestTime >= 0.51 && $url->requestTime <= 0.8,
                                'w-6/12' => $url->requestTime >= 0.81 && $url->requestTime <= 1.2,
                                'w-7/12' => $url->requestTime >= 1.21 && $url->requestTime <= 1.7,
                                'w-8/12' => $url->requestTime >= 1.71 && $url->requestTime <= 2.3,
                                'w-9/12' => $url->requestTime >= 2.31 && $url->requestTime <= 3,
                                'w-10/12' => $url->requestTime >= 3.01 && $url->requestTime <= 3.9,
                                'w-11/12' => $url->requestTime >= 3.91 && $url->requestTime <= 4.89,
                                'text-right py-3', 'px-1', 'text-white', 'w-8'
                                ])></div>
                                    <div class="text-center">
                                        {{ $url->requestTime }} sec
                                    </div>
                                </div>
                            </td>
                        @endif
                        <td class="py-4 px-6">
                            @if($url->active === 1)
                                <span class="inline-flex rounded-full bg-green-400 px-3 py-1 text-s font-semibold leading-5 text-green-800">
                                    Yes
                            </span>
                            @else
                                <span class="inline-flex rounded-full bg-red-500 px-3.5 py-1 text-s font-semibold leading-5 text-red-900">
                                    No
                            </span>
                            @endif

                            @if($url->searchQ !== '')
                                @if($url->foundQuery === 1)
                                    <span class="inline-flex rounded-full bg-green-400 px-3 py-1 text-s font-semibold leading-5 text-green-800">
                                        Yes
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-500 px-3.5 py-1 text-s font-semibold leading-5 text-red-900">
                                        No
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex rounded-full bg-yellow-200 px-3.5 py-1 text-s font-semibold leading-5 text-red-800">
                                ---
                            </span>
                            @endif

                        </td>
                        <td class="py-4">
                            <form method="POST" action="{{ route('url.delete', $url->id) }}">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="text-red-400 focus:ring-4 font-medium text-xs py-1 "
                                    type="submit"
                                    onclick="return confirm('Are you sure that you want to delete {{ $url->url }}?')"
                                >
                                    Delete
                                </button>
                            </form>
                        </td>
                        <td class="py-4">
                            <a href="{{ route('url.update', $url->id) }}">
                                <button
                                    class="text-green-400 focus:ring-4 font-medium text-xs py-1 mr-2 "
                                >
                                    Refresh
                                </button>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <a href="{{ route('url.updateAll') }}">
            <button class="float-left text-white bg-fuchsia-600 font-medium rounded-lg text-sm px-8 py-3 mt-7 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                Refresh All
            </button>
        </a>
    </div>
</x-layout>

<script>
    //reload window every 30 seconds unless user is typing
    setInterval(function (){
        if(document.getElementById('url').value === '' && document.getElementById('searchQ').value === ''){
            document.location.reload();
        }
    }, 30000);
</script>

<style>
    .bg-fuchsia-600{
        background-color: rgb(192 38 211);
    }
</style>
