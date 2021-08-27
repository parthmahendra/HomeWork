
window.onload = function () {

    let inputs = $('.my-text-input')

    if (inputs.val() !== ''){
        var label = $("label[for='" + $(this).attr('id') + "']");
        label.hide()
    }

    inputs.on("input change paste keyup cut select", function() {
        var label = $("label[for='" + $(this).attr('id') + "']");

        if (!$(this).val()) {
            label.show()
        } else {
            label.hide()
        }

        parent = $(this).parent()
        if (this.hasAttribute('pattern')){
            var pattern = this.getAttribute("pattern");
            var re = new RegExp(pattern);
            if (re.test(this.value) || $(this).val() === '') {
                parent.removeClass('is-invalid')
            } else {
                parent.addClass('is-invalid')
            }
        } else {
            parent.removeClass('is-invalid')
        }

    });

}