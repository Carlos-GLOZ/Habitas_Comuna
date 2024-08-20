<x-app-layout>

    <div id="meet" class="rounded-xl">
        <div id="meet_skeleton" style="height: calc(100vh / 1.3);" class="w-full bg-gray-900"></div>
    </div>

        @if (in_array('4', $modulos))
        <div class="pt-4">
            @if(Auth::user()->id == $comunidad->presidente_id || Auth::user()->id == $comunidad->vicepresidente_id)
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="encuesta()">{{ __('Surveys') }}</button>
            @endif

            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="votar()">{{ __('Voting') }}</button>
        </div>
        @endif


    <script src='https://meet.jit.si/external_api.js'></script>
    @vite('resources/js/meet.js')
    <script>

        const domain = "meet.jit.si";
        // Swal.fire({
        //     title: 'Modal sin cerrar al hacer clic fuera',
        //     text: 'Este modal no se cerrará al hacer clic fuera de él',
        //     backdrop: 'static',

        //     allowOutsideClick: false,
        //     showConfirmButton: true,
        //     confirmButtonText: 'Aceptar'
        // });

        Swal.fire({
        title: "{{ __('To enter the meeting you must give consent to record the call.')}}",
        showDenyButton: true,
        showCancelButton: false,
        backdrop: 'static',

        allowOutsideClick: false,
        confirmButtonText: "{{ __('Accept')}}",
        denyButtonText: "{{ __('Deny')}}",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {


                const options = {
                    roomName: '{{$comunidad->meet}}',
                    width: '100%',
                    height: window.innerHeight / 1.3,
                    parentNode: document.querySelector('#meet'),
                    lang: "{{ Auth::user()->language}}",
                    userInfo: {
                        email: '{{ Auth::user()->email}}',
                        displayName: "{{ Auth::user()->name}}"
                    },
                    configOverwrite: {
                        startWithVideoMuted: true,
                        startWithAudioMuted: true
                    },
                };

                const api = new JitsiMeetExternalAPI(domain, options);
                api.executeCommand('setSize', '100%', '100%');

                api.on('ready', () => {

                    api.executeCommand('displayName', '{{ Auth::user()->name}}');

                    api.executeCommand('join', null);
                });
                meet_skeleton.style.display = "none";

                // api.addEventListener('videoConferenceLeft', (event) => {
                //     window.location.href = '{{url("/dashboard")}}';
                // });

            } else if (result.isDenied) {
                window.location.href = '{{url("/dashboard")}}';
            }
        })

        const encuesta =()=>{
            Swal.fire({
                title: '<strong>{{ __("Surveys") }}</strong>',
                // icon: 'info',
                html:
                    `
                    <iframe id="inlineFrameExample"
                        title="Inline Frame Example"
                        width="100%"
                        height="400"
                        src="{{route('componente_encuesta_vista')}}">
                    </iframe>
                    `,
                allowOutsideClick: false,
                showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                showConfirmButton: false,
                width: '80%',

            })
        }

        const votar =(id=0)=>{

            if(id){
                var URL = URL_API+'/votacion/comp/votar/'+id
            }else{
                var URL = "{{route('viewCompListaVotacion')}}"
            }

            Swal.fire({
                title: '<strong>{{ __("Vote") }}</strong>',
                // icon: 'info',
                html:
                    `
                    <iframe id="inlineFrameExample"
                        title="Inline Frame Example"
                        width="100%"
                        height="400"
                        src="${URL}">
                    </iframe>
                    `,
                allowOutsideClick: false,
                showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                showConfirmButton: false,
                width: '80%',

            })
        }


    </script>
</x-app-layout>
