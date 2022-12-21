<div class="bg-gray w-full  mb-20">
    <div class="w-3/6 m-auto bg-gray-200 p-4 grid gap-y-4 rounded-md">
        <p class="text-lg font-bold">Hello !</p>
        <p class="text-sm">You are received this email because you have to verify your email which you just created.</p>
        <button class="bg-gray-500 w-1/5 p-3 rounded-sm  m-auto text-white">Verify Email</button>
        <div class="  border-t-2 border-gray-500 mt-5"></div>
        <p class="text-gray-500 text-sm mb-10">If you're having trouble clicking the "Verify Email" button, copy and
            paste the URL below into your web browser: <a href="{{ $url }}">{{ $url }}</a>
        </p>
        <p class="text-gray-500">Regards</p>
        <p class="text-gray-500">JobPoster</p>
    </div>
</div>
