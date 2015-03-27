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
            beforeSend: function () {
                $("#loading").removeClass("hide");
            },
            success: function (comeback) {
                $("#loading").addClass("hide");
                comeback = $.trim(comeback);
                comeback = comeback.split("|");
                if (comeback[0] === "success") { //Izpis če je nekaj bilo uspešno
                    if (data["clear"] == 1) { //Počisti vse inpute ob uspešni akciji
                        $.each(data, function (index, value) {
                            $("[name=" + index + "]").val("");
                        });
                    }
                    if(data["refresh"] == 1){
                        window.location.reload();
                    }
                    alertify.success(comeback[1]);
                } else if (comeback[0] === "redirect") { //Preusmeritev
                    if ($redirect === "") {
                        window.location.href = comeback[1];
                    } else {
                        window.location.href = $redirect;
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
        $("#loading").removeClass("hide");
        $(".load-content").append("<h3>Iskanje v teku...</h3>");
        var search = $("#search").val();
        search = encodeURI(search);
        var url = location.protocol + "//" + location.host + "/fastSearch.php?query=" + search;
        window.location.href = url;
    }
    //END HITRO ISKANJE

    //TO TOP
    $(document).ready(function () {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 105) {
                $('#totop').fadeIn();
            } else {
                $('#totop').fadeOut();
            }
        });
        $('#totop').click(function () {
            $("html, body").animate({scrollTop: 0}, 400);
            return false;
        });
    });
    //END TO TOP

    //TITLE
    $(document).ready(function () {
        var title = $("h1:first").text();
        var site = "AVTODELI";
        $("title").text(site + " - " + title);
    });
    //END TITLE

    //COOKIES
    $(document).ready(function () {
        if (localStorage.getItem("cookies") === null) {
            $("#cookies").show();
        } else {
            $("#cookies").hide();
        }

        $(document).on("click", ".accept", function () {
            $("#cookies").hide();
            $(".state").show();
            $(".state2").show();
            localStorage.setItem("cookies", "1");
            $(".cookie-state").html("<span class='color-success'>Sprejeli</span>");
        });

        $(document).on("click", ".decline", function () {
            $("#cookies").hide();
            $(".state").show();
            $(".state2").show();
            localStorage.setItem("cookies", "0");
            $(".cookie-state").html("<span class='color-danger'>Zavrnili</span>");
        });

        $(document).on("click", "#reset", function () {
            localStorage.removeItem("cookies");
            $(".state").hide();
            $(".state2").hide();
            $("#cookies").show();
        });
    });
    //END COOKIES
});

window.addEventListener('storage', onStorageEvent, false);

function onStorageEvent(storageEvent) {
    if (storageEvent.key === "logout") {
        location.reload();
    }
}