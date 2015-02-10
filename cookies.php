<?php include_once './header.php'; ?>
<div class="col-lg-12 block-flat top-primary">
    <h1 class="page-header">Piškoti</h1>
    <h3 class="page-header">Kaj so piškoti?</h3>
    <p>
        Piškotek je majhna datoteka, ki se shrani na računalniku ob obisku spletne strani. Piškotki vsebujejo različne informacije, ki jih spletna stran prebere, ko jo ponovno obiščete. Uporablja jih večina sodobnih spletnih strani. <a href="http://eur-lex.europa.eu/legal-content/SL/TXT/HTML/?uri=CELEX:32002L0058&from=SL" target="_blank">Preberi več</a>
    </p>
    <h3 class="page-header">Prikaz obvestila</h3>
    <p>
        Obvestilo bo prikazano, dokler ne boste potrdili ali zavrnili piškotov. Če boste očistili izbiro potrditve/zavrnitve, se obvestilo prikaže nazaj!
    </p>
    <h3 class="page-header">Naši piškoti</h3>
    <p>
        Piškot PHPSESSID je nujno potreben za normalno delovanje vseh storitev in ga ni mogoče zavrniti!
    </p>
    <table class="table table-responsive table-striped table-hover table-bordered">
        <tr>
            <th>
                Ime piškota
            </th>
            <th>
                Namen
            </th>
            <th>
                Trajanje
            </th>
            <th>
                Podjetje
            </th>
        </tr>
        <tr>
            <td>
                PHPSESSID
            </td>
            <td>
                Identifikacija uporabnika, delovanje servisov
            </td>
            <td>
                Do končanja seje
            </td>
            <td>
                Avtodeli 
            </td>
        </tr>
        <tr>
            <td>
                __ga, __gat
            </td>
            <td>
                Statistika ogledov spletne strani
            </td>
            <td>
                Različno
            </td>
            <td>
                Google 
            </td>
        </tr>
    </table>
    <h3 class="page-header state">Stanje vaših piškotov</h3>
    <p class="state" style="display: none;">Vi ste piškote: <span class="cookie-state"></span></p>
    <div class="state2" style="display: none;">
            <h4 class="page-header">Spremeni stanje piškotov</h4>
            <span class="btn btn-success btn-flat accept">Sprejmi</span>
            <span class="btn btn-warning btn-flat" id="reset">Očisti izbiro</span>
            <span class="btn btn-danger btn-flat decline">Zavrni</span>
    </div>
</div>
<script async>
    $(document).ready(function () {
        if (localStorage.getItem("cookies") === null) {
            $(".state").hide();
            $(".state2").hide();
        } else {
            $(".state").show();
            $(".state2").show();
            if (localStorage.getItem("cookies") === "1") {
                $(".cookie-state").html("<span class='color-success'>Sprejeli</span>");
            } else {
                $(".cookie-state").html("<span class='color-danger'>Zavrnili</span>");
            }
        }
    });
</script>
<?php include_once './footer.php'; ?>