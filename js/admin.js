const EPSertsAdmin = {
    toZero: (id) => {
        const input = document.getElementById(id);

        if (input.value == '' ||
            input.value < 0) input.value = 0;
    }
}
