//AJAX FROM
$("from#ajaxForm").on("submit", function () {
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
        success: function(comeback){
            comeback = $.trim(comeback);
            if(comeback === "success"){
                window.location($redirect);
            } else if(comeback === "error1"){
                
            } else if(comeback === "error2"){
                
            }
        }
    });
});