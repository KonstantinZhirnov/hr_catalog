<?
require_once 'AppInit.php';

Block::show("Header");
echo '<h1 class="header">Vestibulum quis sapien</h1>';
if (!isset($_REQUEST['action'])){
?>

<table class="emploee">
  <tr>
    <th>Город</th>
    <td>Киев</td>
  </tr>
  <tr>
    <th>Квалификация</th>
    <td>middle</td>
  </tr>
  <tr>
    <th>Статус</th>
    <td>пригласили на собеседование</td>
  </tr>
  <tr>
    <th>Вакансия</th>
    <td>Lorem ipsum dolor sit amet.</td>
  </tr>
  <tr>
    <th>Контактные данные</th>
    <td>телефон:(111)111-11-11<br />
        скайп: some_skype<br />
        E-mail: <a href="mailto:#">some@mail.net</a></td>
  </tr>
  <tr>
    <th>желаемая зартлата</th>
    <td>1000 uan</td>
  </tr>
  <tr>
    <th>на удаленную</th>
    <td>да</td>
  </tr>
  <tr>
    <th>Резюме</th>
    <td><a href="#">резюме</a></td>
  </tr>
  <tr>
    <th>портфолио</th>
    <td><a href="#">портфолио</a></td>
  </tr>
  <tr>
    <th>менеджер</th>
    <td>Vestibulum quis sapien</td>
  </tr>
  <tr>
    <th>комментарий</th>
    <td>Vivamus nulla magna, hendrerit ac auctor non, consequat eu quam. Cum sociis natoque penatibus et.</td>
  </tr>
</table>
<a href="?id=1&action=edit">редактировать</a>
<?
} else {
?>
<table class="emploee">
  <tr>
    <th>Город</th>
    <td><select>
          <option value="1">Киев</option>
          <option value="2">Харьков</option>
          <option value="3">Полтава</option>
        </select></td>
  </tr>
  <tr>
    <th>Квалификация</th>
    <td><select >
          <option value="1">junior</option>
          <option value="2">middle</option>
          <option value="3">senior</option>
        </select></td>
  </tr>
  <tr>
    <th>Статус</th>
    <td><select >
          <option value="1">резюме на рассмотрении</option>
          <option value="2">делает ТЗ</option>
          <option value="3">отправили ТЗ на проверку</option>
          <option value="3">резерв</option>
          <option value="3">пригласили на собеседование</option>
          <option value="3">выходит работать</option>
        </select></td>
  </tr>
  <tr>
    <th>Вакансия</th>
    <td><select>
          <option value="1">Lorem ipsum dolor sit amet.</option>
          <option value="2">Lorem ipsum dolor sit amet.</option>
          <option value="3">Lorem ipsum dolor sit amet.</option>
        </select></td>
  </tr>
  <tr>
    <th>Контактные данные</th>
    <td><select>
        <option selected="selected">телефон</option>
        <option>skype</option>
        <option>e-mail</option>
      </select>: <input type="text" value="(111)111-11-11" /> <a href="#">удалить</a><br />
        <select>
        <option>телефон</option>
        <option selected="selected">skype</option>
        <option>e-mail</option>
      </select>: <input type="text" value="some_skype" /> <a href="#">удалить</a><br />
        <select>
        <option>телефон</option>
        <option>skype</option>
        <option selected="selected">e-mail</option>
      </select>: <input type="text" value="some@mail.net" /> <a href="#">удалить</a><br />
        <select>
        <option>телефон</option>
        <option>skype</option>
        <option>e-mail</option>
      </select>: <input type="text" />
    </td>
  </tr>
  <tr>
    <th>желаемая зартлата</th>
    <td><input type="text" value="1000 uan" /></td>
  </tr>
  <tr>
    <th>на удаленную</th>
    <td><input type="checkbox" /></td>
  </tr>
  <tr>
    <th>Резюме</th>
    <td><input type="file" /></td>
  </tr>
  <tr>
    <th>портфолио</th>
    <td><input type="file" /></td>
  </tr>
  <tr>
    <th>менеджер</th>
    <td><select>
        <option>Vestibulum quis sapien</option>
        <option>Vestibulum quis sapien</option>
        <option>Vestibulum quis sapien</option>
        <option>Vestibulum quis sapien</option>
      </select></td>
  </tr>
  <tr>
    <th>комментарий</th>
    <td><textarea>Vivamus nulla magna, hendrerit ac auctor non, consequat eu quam. Cum sociis natoque penatibus et.</textarea></td>
  </tr>
</table>
<a href="?id=1">принять</a> <a href="?id=1">отменить</a>
<?php
}
Block::show("Footer");
?>