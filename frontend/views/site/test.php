<div class="col-md-12">
    <h2>Таблица категорий</h2>

    <table class="table">
        <?php foreach($categories as $category) : ?>
            <tr>
                <td><?= $category->id; ?></td>
                <td><?= $category->name; ?></td>
                <td><?= $category->icon; ?></td>
            <tr>
        <?php endforeach; ?>
    </table>
</div>
