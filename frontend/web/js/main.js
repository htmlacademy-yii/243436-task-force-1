var openModalLinks = document.getElementsByClassName("open-modal");
var closeModalLinks = document.getElementsByClassName("form-modal-close");
var overlay = document.getElementsByClassName("overlay")[0];

for (var i = 0; i < openModalLinks.length; i++) {
  var modalLink = openModalLinks[i];

  modalLink.addEventListener("click", function (event) {
    var modalId = event.currentTarget.getAttribute("data-for");

    var modal = document.getElementById(modalId);
    modal.setAttribute("style", "display: block");
    overlay.setAttribute("style", "display: block");

  });
}

function closeModal(event) {
  var modal = event.currentTarget.parentElement;

  modal.removeAttribute("style");
  overlay.removeAttribute("style");
}

for (var j = 0; j < closeModalLinks.length; j++) {
  var closeModalLink = closeModalLinks[j];

  closeModalLink.addEventListener("click", closeModal)
}

document.getElementById('close-modal').addEventListener("click", closeModal);
document.getElementById('close-modal1').addEventListener("click", closeModal);

var starRating = document.getElementsByClassName("completion-form-star");



if (starRating.length) {
  starRating = starRating[0];

  starRating.addEventListener("click", function(event) {
    var stars = event.currentTarget.childNodes;
    var rating = 0;

    for (var i = 0; i < stars.length; i++) {
      var element = stars[i];

      if (element.nodeName === "SPAN") {
        element.className = "";
        rating++;
      }

      if (element === event.target) {
        break;
      }
    }

    var inputField = document.getElementById("rating");
    inputField.value = rating;
  });
}

// var stars = document.querySelectorAll('.completion-form-star .star-disabled');

// var cityDropdown = document.getElementsByClassName('town-select');

// if (cityDropdown.length) {
//   cityDropdown = cityDropdown[0];

//   cityDropdown.addEventListener('change', function(event) {
//     var selectedCity = event.target.value;

//     window.location = '/site/city?city=' + selectedCity;
//   });
// }

const autoCompleteJS = new autoComplete({
  selector: "#autoComplete",
  placeHolder: "Санкт-Петербург, Калининский район",
  data: {
      src: async function test(query) {
        try {
          // Fetch Data from external Source
          const source = await fetch(`https://geocode-maps.yandex.ru/1.x/?geocode=${query}
          &apikey=e666f398-c983-4bde-8f14-e3fec900592a&format=json&results=10
          &ll=37.618920,55.756994&spn=3.552069,2.400552`);
          // Data is array of `Objects` | `Strings`
          const data = await source.json();

          let result = [];
          let featureMember = data.response.GeoObjectCollection.featureMember;

          let lengthArray = featureMember.length;

          for (let i = 0; i < lengthArray; i++) {
            result[i] = featureMember[i].GeoObject.name;
          }

          return result;
        } catch (error) {
          return error;
        }
      }
  },
  resultsList: {
      element: (list, data) => {
          if (!data.results.length) {
              // Create "No Results" message element
              const message = document.createElement("div");
              // Add class to the created element
              message.setAttribute("class", "no_result");
              // Add message text content
              message.innerHTML = `<span>Ничего не найдено по запросу "${data.query}"</span>`;
              // Append message element to the results list
              list.prepend(message);
          }
      },
      noResults: true,
  },
  resultItem: {
      highlight: true
  },
  events: {
      input: {
          selection: (event) => {
              const selection = event.detail.selection.value;
              autoCompleteJS.input.value = selection;
          }
      }
  }
});

let city = document.getElementById('autoComplete');

city.addEventListener('focusout', () => {
  async function addCity() {
    const city = document.getElementById('autoComplete').value;

    let formData = new FormData();

    formData.append('q', city);

    let response = await fetch('./geo', {
      method: 'POST',
      body: formData
    });

    const data = await response.json();

    document.getElementById('lat').value = data.lat;
    document.getElementById('lon').value = data.lon;
    document.getElementById('city_id').value = data.city_id;

    console.log(data.message);
  }

  addCity();
});

