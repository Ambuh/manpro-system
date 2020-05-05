<?php
function md_calendarmarker(){

$cal=System::shared("calendarmarker");

$mrk=new cMark;

$mess="";

if(isset($_POST['add_date'])){

$mrk->importantDate_date=$_POST['important_date'];

$mrk->importantDate_description=$_POST['i_descrip'];

$mess= $cal->markDate($mrk);

}

if((isset($_POST['rem_date']))&(isset($_POST['marked_date']))){

$mess= $cal->removeMarkedDate($_POST['marked_date']);

}


System::enableDatePicker();

$button=new input();

$button->setClass("form_button_add");

$button->setTagOptions("style=\"margin-left:12px;margin-top:5px;float:right;\"");

$button->input("submit","add_date","Add");

$input=new input;

$input->setTagOptions("style=\"margin-left:13px;margin-top:10px;float:left\"");

$input->setId("adts");

$input->makeDatePicker("dd/mm/yyyy","0");

$input->setClass("form_input");

$input->input("text","important_date",System::getArrayElementValue($cal->buffered_vals,0));

?>
<div id="module_inner">
<?php 

$form=new form_control;

echo $form->formHead();

echo $mess; 

?>


<div id=\"form_row\"><div id=\"label\" ><strong style="margin-left:0px;" >New Date</strong></div></div>

<div id=\"form_row\"><?php echo $input->toString();?></div>

<div id=\"form_row\" style=" margin-top:5px;float:left;"><div id=\"label\" ><strong style="margin-left:0px; margin-top:5px;" >Description</strong></div></div>

<div id=\"form_row\" ><textarea style="margin-top:5px; margin-left:12px; font-size:12px;" class="form_input" name="i_descrip"><?php echo System::getArrayElementValue($cal->buffered_vals,1);?></textarea></div>

<div id=\"form_row\" style="float:left; width:100%; margin-left:2px;"><?php echo $button->toString();?></div>

<?php 

$button=new input();

$button->setClass("form_button_add");

$button->setTagOptions("style=\"float:right;margin-left:12px;margin-top:5px;\"");

$button->input("submit","rem_date","Remove");

?>

<div id=\"form_row\">
<div style="float:left; margin-left:5px; margin-top:3px; font-weight:normal; text-indent:0px;">
<?php 

$list=new list_control;

$list->setColumnNames(array("Sel.","Important Date"));

$list->setColumnSizes(array("40px","107px"));

$list->setListId("eds");

$list->setHeaderFontBold();

$list->setAlternateColor("#cbe3f8");

$list->setBackgroundColour("#ffffff");

$list->setSize("170px","100px");

$dates=$cal->getMarkedDates();

for($i=0;$i<count($dates);$i++){

$list->addItem(array("<input type=\"radio\" name=\"marked_date\" value=\"{$dates[$i]->importantDate_id}\" />",$dates[$i]->importantDate_date));

}

$list->showList(true);

?>
</div>
</div>

<div id=\"form_row\"><?php echo $button->toString();?></div>

<?php echo "</form>"; ?>

</div>
<?php
}
?>