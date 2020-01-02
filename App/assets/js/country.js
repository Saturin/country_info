let countryList = null;

$(()=> {
    loadCache();
});

$(document).on("input", $("#search") ,() =>{
    if($("#search").val().length > 0 ){
        $.ajax({
            url : URL + "findCountry",
            data: {
                type: $("#type").val(),
                text: $("#search").val()
            }
        }).done(function(json) {

            if($(".search__results").length === 0) {
                $("#search").closest('div').append(`<div class="search__results"></div>`);
            }
            $(".search__results").html("");

            $.each(json,(i,item) => {
                $(".search__results").append(`<div code="${item.sISOCode}"> ${item.sName} - ${item.sISOCode}</div>`)
            });
        });
    }
}).on('click', $(".search__results div"), (e) => {

    getCountry($(e.target).attr("code"));
    $(".search__results").remove();
});

let loadCache = () => {
    $.ajax({
        url : URL + "loadCache"
    }).done(function(json) {
        countryList = json;
        // renderCountryList();
    });
};

let renderCountryList = () => {
    if(countryList === null) return null;
    $(".country").html("");
    $.each(countryList, (i ,item) => {
        let selectedNode = item.sName == selected ? "active" : "";
        if(selectedNode !== "") {
            $(".country").append(`<div class="${ selectedNode }" code="${item.sISOCode}">${item.sName}</div>`)
        }

    })

};

let getCountry = (code) => {
    $.ajax({
        url : URL + "getCountry",
        data: {
           code: code
        }
    }).done(function(json) {
        let tmp = json.FullCountryInfoResult;
        $("#country").text(tmp.sName);
        $("#capital").text(tmp.sCapitalCity);
        $("#continent").text(tmp.sContinentCode);
        $("#flag").attr("src", tmp.sCountryFlag);
        $("#currency").text(tmp.sCurrencyISOCode);
        $("#prefix").text(tmp.sPhoneCode);

        $("#languages").text("");
        $.each(tmp.Languages, (i,e)=>{
            console.log(e);
            $("#languages").append(e.sName + " ");
        })
      console.log(json);
    });
}




