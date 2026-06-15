@extends('app')

@section('content')
    <div class="flex flex-col w-full">
		<div class="flex flex-row px-10 items-center justify-between w-full min-h-[calc(90dvh-0.25rem)]">
			<div class="flex flex-col w-full md:w-[calc(50%-0.5rem)] md:px-10 items-center md:items-left">
				<span class="text-[5rem] text-[#3B82F6] font-extrabold w-full text-center md:text-left">Anesidora</span>
				<span class="text-[1.5rem] w-full text-center md:text-left">Share More Than Files. Share Responsibilities.</span>
			</div>
			<div class="hidden md:flex w-[calc(50%-0.5rem)]">
				<img src="assets/icon.png" alt="">
			</div>
		</div>
		<div class="flex flex-col gap-3 min-h-screen">
			<span class="text-[1.5rem]">Top Downloads</span>
			<div id="file-lists" class="flex flex-row flex-wrap gap-2 w-full"></div>
		</div>
	</div>

	<script>
		const main = document.getElementById("file-lists")
		async function main_loader(){
			const files = await fetch('/api/files/top').then((r) => { return r.json() })
            console.log(files)
			files.forEach(file => {
				const container = document.createElement("div")
				const head = document.createElement("div")
				const icon = document.createElement("img")
				const type = document.createElement("span")
				const title = document.createElement("span")
				const description = document.createElement("span")

				head.classList.add(
					'flex', 'flex-row', 'w-full'
				)

				type.textContent = file.file.split(".")[file.file.split(".").length - 1]
				type.classList.add(
					'px-3', 'text-[0.75rem]', 'rounded-full',
					'bg-[#283044]'
				)
				head.appendChild(type)

				container.classList.add(
					'flex', 'flex-col', 'aspect-video',
					'rounded', 'bg-[#0f172a]',
					'w-[calc(50%-0.5rem)]', 'md:w-[calc(25%-0.5em)]', 'p-2',
					'cursor-pointer', 'hover:bg-slate-800', 'transition-colors'
				)
				container.onclick = () => {
					location.href = `/file/${file.public_url}`
				}

				title.textContent = file.file
				description.textContent = file.description

				container.appendChild(head)
				container.appendChild(title)
				container.appendChild(description)

				main.appendChild(container)
			})
		}
		main_loader()
	</script>
@endsection
