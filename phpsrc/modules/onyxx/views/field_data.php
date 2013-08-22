<div class="row">
    <div class="span6 offset3">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Inferred Type</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($fields as $field) : ?>
                <tr>
                    <td><?= $field->name ?></td>
                    <td><?= $field->type ?></td>
                    <td><?= infer_type($field->type) ?></td>
                </tr>
        <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>