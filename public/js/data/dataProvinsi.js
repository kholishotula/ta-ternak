let provinceSel = document.getElementById('provinsi');
// let regencySel = document.getElementById('kab_kota');

let defaultOption = document.createElement('option');
defaultOption.text = 'Pilih Provinsi';
provinceSel.add(defaultOption);

let option;

const ProvinceApi = 'http://www.emsifa.com/api-wilayah-indonesia/api/provinces.json';

$.getJSON(ProvinceApi, function (data) {
    $.each(data, function (key, entry) {
        option = document.createElement('option');
      	option.text = entry.name;
      	// option.value = entry.id + '-' + entry.name;
      	option.value = entry.name;
      	provinceSel.add(option);
    })
});
// fetch(ProvinceApi)  
//   .then(  
//     function(response) {  
//       if (response.status !== 200) {  
//         console.warn('Looks like there was a problem. Status Code: ' + 
//           response.status);  
//         return;  
//       }

//       // Examine the text in the response  
//       response.json().then(function(data) {      
//     	for (let i = 0; i < data.length; i++) {
//           provinces.push(data[i]);
//     	}    
//       });  
//     }  
//   )  
//   .catch(function(err) {  
//     console.error('Fetch Error -', err);  
//   });

// window.onload = function() {
//     let option;
//     for (let i = 0; i < provinces.length; i++) {
//         option = document.createElement('option');
//       	option.text = provinces[i].name;
//       	option.value = provinces[i].id + '-' + provinces[i].name;
//       	provinceSel.add(option);
//     }
// }

// provinceSel.onchange = function() {
//     provinceId = provinceSel.value.split('-')[0];

//     const RegencyApi = 'http://www.emsifa.com/api-wilayah-indonesia/api/regencies/' + provinceId + '.json';

//     $.getJSON(RegencyApi, function (data) {
//         $.each(data, function (key, entry) {
//             option = document.createElement('option');
//             option.text = entry.name;
//             option.value = entry.id + '-' + entry.name;
//             regencySel.add(option);
//         })
//     });
// }