<x-layout>
    <h1 class="text-5xl font-extrabold text-center dark:text-white mt-8 mb-12">Website Monitoring EngageMedia</h1>
    <div class="m-auto max-w-5xl">
        <div class="mt-12 pb-16">
            <section class="p-6 bg-white border-b mr-16 ml-16">
                <form method="POST" action="/url">
                    @csrf
                    <div class="relative">
                        <label
                            for="url"
                            class="block mb-2 uppercase font-bold text-xs text-gray-700"
                        >
                            Neue URL
                        </label>
                        <input
                            class="block w-full p-4  text-sm text-gray-900 border border-gray-300 rounded-lg bg-white-100 mb-3 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            type="text"
                            name="url"
                            id="url"
                            placeholder="Add new URL to monitor..."
                            required
                        >
                        @error('url')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                        <label
                            for="searchQ"
                            class="block mb-2 uppercase font-bold text-xs text-gray-700"
                        >
                            Text, der auf der Website ist
                        </label>
                        <input
                            class="block w-full p-4  text-sm text-gray-900 border border-gray-300 rounded-lg bg-white-100 mb-3 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            type="text"
                            name="searchQ"
                            id="searchQ"
                            placeholder="Wort/ Satz... (Case Sensitive)"
                        >
                        @error('searchQ')
                        <p class="text-red-500 text-xs mt-2">The "Wort/Satz" field is required</p>
                        @enderror

                        <button
                            type="submit"
                            class="text-white font-medium rounded-lg text-sm px-8 py-3 bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            Add
                        </button>
                    </div>
                </form>
            </section>
        </div>
        <table class="mt-5 w-full text-sm text-left text-gray-500 dark:text-gray-400 border border-gray-300">
            <thead class="text-xs text-gray-700 uppercase bg-blue-100 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-6">
                    Website
                </th>
                <th scope="col" class="py-3 px-6">
                    Last Checked
                </th>
                <th scope="col" class="py-3 px-6">
                    Request-Time
                </th>
                <th scope="col" class="py-3 px-6">
                    Active&#160;&#160;&#160;Found
                </th>
                <th scope="col" class="py-3 px-6"></th>
                <th scope="col" class="py-3 px-6"></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($urls as $url)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $url->url }}
                    </th>
                    <td class="py-4 px-6 text-sm">
                        {{ $url->updated_at->diffForHumans() }}
                    </td>
                    @if($url->requestTime >= 4.9)
                        <td class="py-4 px-6 text-red-500 font-bold">
                            >{{ $url->requestTime }} sec
                        </td>
                    @else
                        <td class="py-4 px-6">
                            {{ $url->requestTime }} sec
                        </td>
                    @endif
                    <td class="py-4 px-6">
                        @if($url->active === 1)
                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-s font-semibold leading-5 text-green-800">
                                    Yes
                            </span>
                        @else
                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-s font-semibold leading-5 text-red-800">
                                    No
                            </span>
                        @endif
                        @if($url->foundQuery === 1)
                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-s font-semibold leading-5 text-green-800">
                                Yes
                        </span>
                        @else
                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-s font-semibold leading-5 text-red-800">
                                No
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

        <a href="{{ route('url.updateAll') }}">
            <button class="float-left text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-8 py-3 mt-7 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                Refresh All
            </button>
        </a>
    </div>

</x-layout>
