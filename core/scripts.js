$(document).ready(function () {
    //AJAX FROM
    $(".ajaxForm").on("submit", function () {
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
                $redirect = value; //Kam te preusmeri funkcija, če je pošiljanje forme uspešno in se v formi pošlje lokacija
            } else {
                $redirect = "";
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
                if (comeback[0] === "success") { //Izpis če je nekaj bilo uspešno
                    alertify.success(comeback[1]);
                } else if (comeback[0] === "redirect") { //Preusmeritev
                    if ($redirect === "") {
                        window.location = comeback[1];
                    } else {
                        window.location = $redirect;
                    }
                } else if (comeback[0] === "error") { //Error
                    alertify.error(comeback[1]);
                }
            }
        });
        return false;
    });
    //END AJAX FORM

    //HITRO ISKANJE
    //Če je pritisnjen enter v hitrem iskanju naj išče
    $("#search").keypress(function (e) {
        if (e.which == 13) {
            fastSearch();
        }
    });

    function fastSearch() {
        var search = $("#search").val();
        search = encodeURI(search);
        window.location = "fastSearch.php?query=" + search;
    }

    $(document).on("click", "#fastSearch", function () {
        showSearch();
    });

    function showSearch() {
        if ($("#search").is(':visible')) {
            hideSearch();
        } else {
            $("#search").show("slide", {direction: "right"}, 350);
        }
    }
    function hideSearch() {
        $("#search").hide("slide", {direction: "right"}, 350);
    }
    //END HITRO ISKANJE
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip();
})