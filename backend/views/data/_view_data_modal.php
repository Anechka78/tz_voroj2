<?php

/** @var yii\web\View $this */
/** @var \common\models\Data $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\web\View;
use yii\widgets\Pjax;


?>
<?php Modal::begin([
    'title' => Html::tag('h4', 'View data info'),
    'id' => 'backend___data___view_modal',
    'size' => Modal::SIZE_LARGE,
    'options' => ['class' => 'fade modal-v2'],
    'closeButton' => false,
    'footer' => Html::a('Cancel', "#", ['class' => 'btn btn-default', 'style' => 'font-weight: bold; line-height: 28px;', "data-bs-dismiss" => "modal"])

]);

Pjax::begin(['id' => 'backend___data___view_form_container', 'enablePushState' => false]);
function generateList($json) {
    $data = json_decode($json, true);
    $html = '<ul>';
    foreach ($data as $key => $value) {
        $type = gettype($value);
        $html .= '<li>' . $key . ' (' . $type . ')';
        if ($type === 'array' || $type === 'object') {
            $html .= '<span class="expand-collapse">( - )</span>';
            $html .= generateList(json_encode($value));
        } else {
            $html .= ' : ' . $value;
        }
        $html .= '</li>';
    }
    $html .= '</ul>';

    return $html;
}


if(!empty($model->data)){
    echo generateList($model->data);
}else{
    echo 'No data info';
}
?>



<?php
Pjax::end();
Modal::end();
$js = <<<HERE
$(function() {
	$("body").on("click", ".expand-collapse", function () {
		let parentLi = $(this).parent("li");
		let isExpanded = parentLi.hasClass("expanded");
		parentLi.toggleClass("expanded");
		parentLi.find("ul").toggleClass("collapsed");
		$(this).html(isExpanded ? "( - )" : "( + )");
	});
});
HERE;

$this->registerJs($js, View::POS_END);