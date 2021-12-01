Dropzone.autoDiscover = false;

var dropzone = new Dropzone(".dropzone", {
    // url: window.location.href, //адрес отправки файлов
    url: "create/upload",

    paramName: "clips",
    uploadMultiple: true,
    dictDefaultMessage: '<span class="dz-message" style="position: relative; top: 0px;">Добавить новый файл</span>',

    acceptedFiles: 'image/*',

    // addRemoveLinks: true,
    dictRemoveFile: 'Удалить',
    dictRemoveFileConfirmation: 'Вы уверены что хотете удалить файл?',

    clickable: true,

    maxFiles: 3,
    dictMaxFilesExceeded: "Достигут max лимит фалов, разрешено {{maxFiles}}",
    init: function() {
        this.on('addedfile', function(file) {
            if (this.files.length > 3) {
                this.removeFile(this.files[3]);
                alert("Максимальное количество 3 файла!");
            }
        });
    },

    parallelUploads: 100,

    headers: {
        'X-CSRF-Token': yii.getCsrfToken(),
    }

    // previewTemplate: '<a href="#"><img data-dz-thumbnail alt="Фото работы"></a>',
});

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

      if (!city) {
          document.getElementById('city_id').value = null;
          return;
      }

      let formData = new FormData();

      formData.append('q', city);

      let response = await fetch('./geo', {
          method: 'POST',
          body: formData,
          headers: {
              'X-CSRF-Token': yii.getCsrfToken(),
          }
      });

      const data = await response.json();

      document.getElementById('city_id').value = data.city_id;

      console.log(data.message);
  };

  addCity();
});

const checkboxContainer = document.getElementById('tasksform-category');

checkboxContainer.style.cssText = `
    display: flex;
    max-width: 740px;
    flex-wrap: wrap;
`;
