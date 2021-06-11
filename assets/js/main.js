

// $('.RemoveFromCartBtn').each(function(i,e){
//     $(e).on('click', function(){
//         let url = $(e).attr('path');
//         console.log(url)
//         $.post(url).then(function(data){            
//             $(e).parent().parent().remove();
//         })
//     })
// })

// document.querySelectorAll(".RemoveFromCartBtn").forEach(function (e) {
//     e.addEventListener("click", function () {
//       fetch(e.getAttribute('path')).then(function (response) {
//         return response.text();
//       })
//       .then(function (response){
//           console.log(response)
//         // document.querySelector('.msg').innerText = response.message
//         // document.querySelector('.totalPrice').innerText = response.price
//         let parser = new DOMParser();
//         response = parser.parseFromString(response, 'text/html');
//         let $newHtml = response.querySelector('#panier')
//         document.querySelector('#panier').replaceWith($newHtml);
//       })
      
//     });

//   }) 

// document.querySelectorAll(".Search").forEach(function (e) {
//     e.addEventListener("keyup", function () {
//       fetch(e.getAttribute('path')).then(function (response) {
//         return response.text();
//       })
//       .then(function (response){
//           console.log(response)
//         let parser = new DOMParser();
//         response = parser.parseFromString(response, 'text/html');
//         let $newHtml = response.querySelector('#Search')
//         document.querySelector('#Search').replaceWith($newHtml);
//       })
      
//     });

//   }) 




document.querySelectorAll(".AddToCartBtn").forEach(function (e) {
    e.addEventListener("click", function () {
    fetch(e.getAttribute('path')).then(function (response) {
        return response.json();
    })
    .then(function (response){
        document.querySelector('.msg').innerText = response.message
    })
    
    });

}) 
