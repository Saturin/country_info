$(()=>{
    render();
});
$(document).on('click', '.sortableColumn' , _sortableColumnClick);
let render = () => {

    $(".lista").html(`
        <table>
        <thead>
            <tr>
                <th class="sortableColumn">Nazwa</th>
                <th class="sortableColumn">Stolica</th>
                <th class="sortableColumn">Kontynent</th>
                <th class="sortableColumn">Waluta</th>
                <th class="sortableColumn">Języki</th>
                <th class="sortableColumn">Flaga</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    `);

    $.ajax({
        url: URL +"/fullList"
    }).done((json)=>{
        console.log(json)
        $.each(json,(i,e)=>{
            $(".lista").find("table").find("tbody").append(drawTr(e));
        })
    })
}

let drawTr = (e) => {
    let languages;
    console.log(typeof e.Languages.tLanguage);
    if(e.Languages.tLanguage === undefined){
        languages = "";
    } else if(typeof e.Languages.tLanguage === "object") {
        languages = e.Languages.tLanguage.sName;
    } else {
        languages  = e.Languages.tLanguage.map((e) => {
            return e.sName;
        }).join(',');
    }

    languages = languages == 'undefined' ? "nieznany" :languages;

    return `<tr>
        <td>${e.sName}</td>
        <td>${e.sCapitalCity}</td>
        <td>${e.sContinentCode}</td>
        <td>${e.sCurrencyISOCode}</td>
        <td>${languages}</td>
        <td><img style="width:70px;" src="${e.sCountryFlag}" alt="${e.sName}"></td>
    </tr>`;
}