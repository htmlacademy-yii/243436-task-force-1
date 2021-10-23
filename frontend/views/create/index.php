<?php
    use yii\widgets\ActiveForm;
?>

<section class="create__task">
    <h1>Публикация нового задания</h1>
    <div class="create__task-main">
        <?php $form = ActiveForm::begin([
            'enableClientValidation' => false,
            'id' => 'task-form',
            'options' => ['class' => 'create__task-form form-create'],
            'fieldConfig' => [
                'template' => "{label}\n
                    {input}\n
                    <span style='color: #8a8a8d;'>{hint}</span>\n
                    <span class='registration__text-error'>{error}</span>",
                'options' => ['class' => 'field-container'],
            ]
        ]); ?>

            <?= $form->field($tasks_form, 'name')
                ->textInput(['class' => 'input textarea', 'id' => 10, 'placeholder' => 'Повесить полку'])
                ->label('Мне нужно', ['for' => 10])
                ->hint('Кратко опишите суть работы');
            ?>

            <?= $form->field($tasks_form, 'description')
                ->textarea(['class' => 'input textarea', 'rows' => 7, 'id' => 11, 'placeholder' => 'Опишите задание'])
                ->label('Подробности задания', ['for' => 11])
                ->hint('Укажите все пожелания и детали, чтобы исполнителям было проще соориентироваться');
            ?>

            <?= $form->field($tasks_form, 'category_id', [
                    'template' => "{label}\n
                        {input}\n
                        <span style='color: #8a8a8d;'>{hint}</span>\n
                        <span class='registration__text-error'>{error}</span>",
                    'labelOptions' => ['for' => 12]
                    ])
                ->dropdownList($category_list, [
                    'value' => $tasks_form->category_id,
                    'class' => 'multiple-select input multiple-select-big',
                    'id' => 12,
                    'size' => 1,
                ])
                ->hint('Выберите категорию');
            ?>

            <div class="field-container">
                <label>Файлы</label>
                <span>Загрузите файлы, которые помогут исполнителю лучше выполнить или оценить работу</span>
                <div class="create__file dropzone">

                </div>
            </div>

            <?= $form->field($tasks_form, 'address')
                ->textInput([
                    'class' => 'input-navigation input-middle input',
                    'id' => 'autoComplete',
                    'placeholder' => 'Санкт-Петербург, Калининский район',
                    'type' => 'search',
                    'dir' => 'ltr',
                    'spellcheck' => false,
                    'autocorrect' => 'off',
                    'autocomplete' => 'off',
                    'autocapitalize' => 'off',
                ])
                ->label('Локация', ['for' => 13])
                ->hint('Укажите адрес исполнения, если задание требует присутствия');
            ?>

            <?= $form->field($tasks_form, 'lat')
                ->hiddenInput([
                    'id' => 'lat',
                ])
                ->label(false);
            ?>

            <?= $form->field($tasks_form, 'lon')
                ->hiddenInput([
                    'id' => 'lon',
                ])
                ->label(false);
            ?>

            <?= $form->field($tasks_form, 'city_id')
                ->hiddenInput([
                    'id' => 'city_id',
                ])
                ->label(false);
            ?>

            <div class="create__price-time">
                <?= $form->field($tasks_form, 'budget', [
                        'options' => ['tag' => 'div', 'class' => 'field-container create__price-time--wrapper']
                    ])
                    ->textInput([
                        'class' => 'input textarea input-money',
                        'id' => 14,
                        'placeholder' => '1000',
                    ])
                    ->label('Бюджет', ['for' => 14])
                    ->hint('Не заполняйте для оценки исполнителем');
                ?>

                <?= $form->field($tasks_form, 'expire', [
                        'options' => ['tag' => 'div', 'class' => 'field-container create__price-time--wrapper']
                    ])
                    ->textInput([
                        'class' => 'input-middle input input-date',
                        'id' => 15,
                        'placeholder' => '10.11, 15:00',
                        'type' => 'date'
                    ])
                    ->label('Сроки исполнения', ['for' => 15])
                    ->hint('Укажите крайний срок исполнения');
                ?>
            </div>

        <?php ActiveForm::end(); ?>

        <div class="create__warnings">
            <div class="warning-item warning-item--advice">
                <h2>Правила хорошего описания</h2>
                <h3>Подробности</h3>
                <p>Друзья, не используйте случайный<br>
                контент – ни наш, ни чей-либо еще. Заполняйте свои
                макеты, вайрфреймы, мокапы и прототипы реальным
                содержимым.</p>
                <h3>Файлы</h3>
                <p>Если загружаете фотографии объекта, то убедитесь,
                что всё в фокусе, а фото показывает объект со всех
                ракурсов.</p>
            </div>
            <?php if (!empty($tasks_form->getErrors())) : ?>
                <div class="warning-item warning-item--error">
                    <h2>Ошибки заполнения формы</h2>
                    <?php foreach($tasks_form->getErrors() as $key=>$value) : ?>
                        <h3>Поле «<?= $tasks_form->attributeLabels()[$key] ?>»</h3>
                        <p><?= $value[0]; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <button form="task-form" class="button" type="submit">Опубликовать</button>
</section>


<script src="js/dropzone.js"></script>
<script>
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

        parallelUploads: 100

        // previewTemplate: '<a href="#"><img data-dz-thumbnail alt="Фото работы"></a>',
    });
</script>


<script>
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
</script>
