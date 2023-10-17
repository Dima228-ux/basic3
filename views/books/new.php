<?php
/**
 * @var $books \app\tables\Books[]
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
                <h3 class="box-title">New Books</h3>
            </div>
            <div class="box-body no-padding">
                <div class="table-responsive">
                    <table id="table" class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th class="text-left"><i class="fa fa-gear fa-lg"></i></th>
                            <th class="text-left"><i class="fa fa-gear fa-lg"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($books)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No books was found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($books as $book): ?>
                                <tr data-id="<?= $book['id'] ?>">
                                    <td><?= $book['id'] ?></td>
                                    <td><?= Html::decode($book['title']); ?></td>
                                    <?php if ($book['thumbnail']) : ?>
                                        <td><img src="<?= Hellper::BASE_IMAGE_URL . $book['thumbnail'] ?>" width="50%"
                                        </td>
                                    <?php endif; ?>
                                    <td><?= $book['status'] ?></td>
                                    <td class="text-left"> <?= Html::a('Edit', ['/books/edit-book', 'id' => $book['id']], ['class' => 'btn btn-mini btn-primary']) ?></td>
                                    <td class="text-left"><?= Html::a(
                                            'Delete',
                                            [
                                                '/books/delete-book',
                                                'id' => $book['id'],

                                            ],

                                            [
                                                'class' => 'btn btn-mini btn-danger',
                                                'data' => [
                                                    'confirm' => 'Are you sure you want to delete this book: ' . $book->id . '?',                                                    'method' => 'post',
                                                ],
                                            ])
                                        ?></td>
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
