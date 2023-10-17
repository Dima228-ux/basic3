<?php
/**
 * @var $contacts \app\tables\Contact[]
 */

use app\models\Lib\Hellper;
use yii\bootstrap4\Html;
use app\tables\Books;
use yii\helpers\Url;

?>

<div class="row">
    <div class="col-xs-12 col-md-10">
        <div class="box box-body box-success">
            <div class="box-header">
                <h3 class="box-title">Applications list</h3>
            </div>
            <div class="box-body no-padding">
                <div class="table-responsive">
                    <table id="table" class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Body</th>
                            <th>Phone Number</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($contacts)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No books was found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($contacts as $contact): ?>
                                <tr data-id="<?= $contact['id'] ?>">
                                    <td><?= $contact['id'] ?></td>
                                    <td><?= Html::decode($contact['name']); ?></td>
                                    <td><?= $contact['email'] ?></td>
                                    <td><?= $contact['subject'] ?></td>
                                    <td><?= $contact['body'] ?></td>
                                    <td><?= $contact['phone'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
