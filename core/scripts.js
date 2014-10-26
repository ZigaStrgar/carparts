$(document).ready(function () {
    //AJAX FROM
    $("#ajaxForm").on("submit", function () {
        //Pregleda osnovne elemente obrazca
        var $this = $(this),
                url = $this.attr('action'), //URL kam naj se pošljejo podatki
                method = $this.attr('method'), //Metoda za pošiljanje podatkov (POST, GET)
                data = {}; //Tabela, ki sprejme podatke za pošiljanje

        //Funkcija, ki pogleda vse elemente obrazca in jih nato shrani v tabelo data
        $this.find('[name]').each(function (index, value) {
            var $this = $(this),
                    name = $this.attr('name'), //Ime, ki ga sprejme $_POST["name"]
                    value = $this.val(); //Vrednost $_POST["name"]
            if (name === 'redirect')
            {
                $redirect = value; //Kam te preusmeri funkcija, če je pošiljanje forme uspešno
            }
            data[name] = value; //Tabela se polni s podatki
        });
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function (comeback) {
                comeback = $.trim(comeback);
                comeback = comeback.split("|");
                if (comeback[0] === "success") {
                    window.location = $redirect;
                } else if (comeback[0] === "redirect") {
                    window.location = comeback[1];
                } else {
                    alertify.error(comeback);
                }
            }
        });
        return false;
    });
    //END AJAX FORM

    //HITRO ISKANJE
    //Ko klikneš na hitro iskanje pregleda vsebino okvirja in če vsebuje Hitro iskanje ga izbriše
    $("#fastSearch").on("click", function () {
        if ($(this).text() === "Hitro iskanje") {
            $(this).text("");
        }
    });

    //Ko izgubi fokus pregleda kakšna je vsebina in če je prazna doda nazaj besedilo Hitro iskanje v nasprotnem pusti vnešeno besedilo
    $("#fastSearch").focusout(function () {
        if ($(this).text() === "") {
            $(this).text("Hitro iskanje");
        }
    });

    //Če je pritisnjen enter v hitrem iskanju naj išče
    $("#fastSearch").keypress(function (e) {
        if (e.which == 13) {
            fastSearch();
        }
    });

    function fastSearch() {
        var search = $("#fastSearch").text();
        search = encodeURI(search);
        window.location = "fastSearch.php?query=" + search;
    }
    //END HITRO ISKANJE
});
