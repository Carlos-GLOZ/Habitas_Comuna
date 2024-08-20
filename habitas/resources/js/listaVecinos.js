
const translate = {
    es: {
        BlockUser: "¿Bloquear usuario de la comunidad?",
        UnblockUser: "¿Desbloquear usuario de la comunidad?",
        UserOut: '¿Echar al usuario de la comunidad?',
        UserBlocked: "El usuario podrá volver a unirse con el código",
        UserUnblocked: "El usuario podrá ser desbloqueado más tarde",
        Confirm: "Confirmar",
        Cancel: "Cancelar",
    },
    en: {
        BlockUser: "Block user from community?",
        UnblockUser: "Unblock user from community?",
        UserOut: 'Kick user from community?',
        UserBlocked: "The user will be able to join with the code",
        UserUnblocked: "The user will be unblocked later",
        Confirm: "Confirm",
        Cancel: "Cancel",
    }
}

// Formularios
const formBlacklistComunidad = document.getElementsByClassName('form-blacklist-comunidad');
const formUnblacklistComunidad = document.getElementsByClassName('form-unblacklist-comunidad');
const formEcharComunidad = document.getElementsByClassName('form-echar-comunidad');

// Event listeners de los formularios
for (let i = 0; i < formBlacklistComunidad.length; i++) {
    const form = formBlacklistComunidad[i];

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        Swal.fire({
            title: trans("Blacklist user from the community?"),
            text: trans("The user can be unblacklisted later"),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: trans("Confirm"),
            cancelButtonText: trans("Cancel")
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })

    })
}

for (let i = 0; i < formUnblacklistComunidad.length; i++) {
    const form = formUnblacklistComunidad[i];

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        Swal.fire({
            title: trans("Unblacklist user from community?"),
            text: trans("The user will be able to join with the code"),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: trans("Confirm"),
            cancelButtonText: trans("Cancel")
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })

    })
}

for (let i = 0; i < formEcharComunidad.length; i++) {
    const form = formEcharComunidad[i];

    form.addEventListener('click', (e) => {
        e.preventDefault();

        Swal.fire({
            title: trans("Kick user from community?"),
            text: trans("The user will be able to join with the code"),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: trans("Confirm"),
            cancelButtonText: trans("Cancel")
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })

    })
}
