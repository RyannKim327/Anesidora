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
		<div id="top-base" class="flex flex-col gap-3 min-h-screen hidden">
			<span class="text-[1.5rem]">Top Downloads</span>
			<div id="file-lists" class="flex flex-row flex-wrap gap-2 w-full"></div>
		</div>
	</div>

	<script>
		const main = document.getElementById("file-lists")
		async function main_loader(){
			const files = await fetch('/api/files/top').then((r) => { return r.json() })

            if(files.length > 0){
                document.getElementById('top-base').classList.remove("hidden")
            }

			files.forEach(file => {
				const card = document.createElement("div")
				card.classList.add(
					'flex', 'flex-col', 'bg-[#0f172a]', 'rounded-xl', 'border', 'border-slate-800',
					'w-[calc(50%-0.5rem)]', 'md:w-[calc(25%-0.5rem)]', 'p-5',
					'hover:border-blue-500/50', 'hover:bg-slate-800/50', 'transition-all', 'duration-300',
					'group', 'cursor-pointer'
				)
				card.onclick = () => {
					location.href = `/file/${file.public_url}`
				}

				const iconContainer = document.createElement("div")
				iconContainer.classList.add('flex', 'items-center', 'justify-center', 'mb-4', 'h-16', 'w-16', 'rounded-lg', 'bg-blue-500/10', 'text-blue-500', 'mx-auto')

				const icon = document.createElement("i")
				const ext = file.file.split(".").pop().toLowerCase()
				let iconClass = "fa-file-o"

				if (['jpg', 'jpeg', 'png', 'gif', 'svg'].includes(ext)) iconClass = "fa-file-image-o"
				else if (['pdf'].includes(ext)) iconClass = "fa-file-pdf-o"
				else if (['zip', 'rar', '7z'].includes(ext)) iconClass = "fa-file-archive-o"
				else if (['mp3', 'wav'].includes(ext)) iconClass = "fa-file-audio-o"
				else if (['mp4', 'mkv', 'avi'].includes(ext)) iconClass = "fa-file-video-o"
				else if (['doc', 'docx'].includes(ext)) iconClass = "fa-file-word-o"
				else if (['xls', 'xlsx'].includes(ext)) iconClass = "fa-file-excel-o"

				icon.className = `fa ${iconClass} fa-2x`
				iconContainer.appendChild(icon)

				const content = document.createElement("div")
				content.classList.add('flex', 'flex-col', 'gap-1', 'text-center', 'mb-6', 'flex-grow')

				const title = document.createElement("span")
				title.textContent = file.file.split(".")[0]
				title.classList.add('text-sm', 'font-bold', 'text-slate-100', 'truncate', 'w-full')

				const description = document.createElement("span")
				description.textContent = file.description || 'No description provided'
				description.classList.add('text-xs', 'text-slate-400', 'line-clamp-2', 'min-h-[2rem]')

				content.appendChild(title)
				content.appendChild(description)

				const footer = document.createElement("div")
				footer.classList.add('mt-auto')

				const button = document.createElement("button")
				button.innerHTML = 'View File <i class="fa fa-arrow-right ml-2 text-[10px]"></i>'
				button.classList.add(
					'w-full', 'py-2.5', 'bg-blue-600', 'hover:bg-blue-500',
					'text-white', 'rounded-lg', 'transition-colors', 'text-xs', 'font-semibold',
					'flex', 'items-center', 'justify-center'
				)

				footer.appendChild(button)
				card.appendChild(iconContainer)
				card.appendChild(content)
				card.appendChild(footer)

				main.appendChild(card)
			})
		}
		main_loader()
	</script>
@endsection
