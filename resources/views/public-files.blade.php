@extends('app')

@section('title', 'Public Files')

@section('content')
    <div class="flex flex-col gap-3 min-h-screen">
        <div class="text-left mb-10">
            <h1 class="text-xl md:text-5xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-600 mb-4">
                Public Files
            </h1>
        </div>
		<div id="file-lists" class="flex flex-row flex-wrap gap-2 w-full"></div>
	</div>
	<script>
		const main = document.getElementById("file-lists")
		async function main_loader(){
			const files = await fetch('/api/files/public').then((r) => { return r.json() })
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
