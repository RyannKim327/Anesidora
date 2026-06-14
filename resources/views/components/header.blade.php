<div class="flex flex-row items-center w-full h-[calc(10%+1rem)] justify-between px-5 bg-[#0f172a] border-b-[#4c58a6] border-b-1">
  <div class="flex flex-row items-center gap-5 md:gap-10 h-full">
    <span class="flex flex-row text-[1.25rem] text-[#3B82F6] font-extrabold">A<span class="hidden md:flex">nesidora</span></span>
    <ul class="flex flex-row gap-3 md:gap-2">
      <li class="cursor-pointer" onclick="location.href = '/'">
        <span class="hidden md:flex">Home</span>
        <i class="flex md:hidden! fa fa-regular fa-home"></i>
      </li>
      <li class="cursor-pointer" onclick="location.href = '/about'">
        <span class="hidden md:flex">About</span>
        <i class="flex md:hidden! fa-regular fa-info"></i>
      </li>
      <li class="cursor-pointer" onclick="location.href = 'files'">
        <span class="hidden md:flex">Public Files</span>
        <i class="flex md:hidden! fa fa-regular fa-folder-open"></i>
      </li>
    </ul>
  </div>
  <div class="flex flex-row gap-5 md:gap-10 items-center h-full">
    <input placeholder="Search File" class="border-[#4c58a6] border rounded-full px-5 py-1" />
    @if (false)
      <div class="flex flex-row gap-2">
        <button class="bg-[#3B82F6] text-[#f8fafc] rounded-full px-5 py-1">Upload</button>
        <button class="border border-[#64748b] rounded-full px-5 py-1">Profile</button>
      </div>
    @else
      <div class="flex flex-row gap-2">
        <button class="bg-[#3B82F6] text-[#f8fafc] rounded-full px-5 py-1">Register</button>
        <button class="border border-[#64748b] rounded-full px-5 py-1">Login</button>
      </div>
    @endif
  </div>
</div>
