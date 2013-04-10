
function sendRequest(id, params, postback) {
  $("#" + id).load('http://'+document.location.hostname + '/block.php',params, postback);
}

function getVacancyEdit(id) {
  sendRequest(id, {block:'VacancyItem', action:'edit', id:id}, function(){
    $("#" + id).addClass('edit');
  });
}

function getVacancyItem(id) {
  sendRequest(id, {block:'VacancyItem', action:'item', id:id}, function(){
    $("#" + id).removeClass();
  });
}

function saveItem(id) {
  params =$('#vacancyEdit').serializeArray();
  sendRequest(id, params, function(){
    if(id !== 'new') {
      $("#" + id).removeClass();
    } else {
      newId = $('#new td:first').html();
      $('#new').attr("id", newId);
      $('#vacanciesList tr:last').after('<tr  id="new"><td></td><td></td><td></td><td><a href="javascript:void(0);" onclick="addItem(\'new\');">добавить</a></td></tr>');
    }
  });
}

function addItem() {
  $("#new").load('http://'+document.location.hostname + '/block.php', {block:'VacancyItem', action:'edit'});
}

