<?
require_once 'AppInit.php';

Block::show("Header");
?>
<h1 class="header">Кандидаты</h1>
<form>
  <table>
    <tr>
      <th>Ид</th>
      <th>Ф.И.О.</th>
      <th>Город &or;</th>
      <th>Квалификация</th>
      <th>Статус &and;</th>
      <th>Вакансия</th>
      <th>Контактные данные</th>
      <th>желаемая зартлата</th>
      <th>на удаленную</th>
      <th>дополнительные данные</th>
      <th>менеджер</th>
      <th>комментарий</th>     
    </tr>
    <tr>
      <td>фильтры</td>
      <td><input type="text"></td>
      <td>
        <select>
          <option value="1">Киев</option>
          <option value="2">Харьков</option>
          <option value="3">Полтава</option>
        </select>
      </td>
      <td>
        <select >
          <option value="1">junior</option>
          <option value="2">middle</option>
          <option value="3">senior</option>
        </select>
      </td>
      <td>
        <select >
          <option value="1">резюме на рассмотрении</option>
          <option value="2">делает ТЗ</option>
          <option value="3">отправили ТЗ на проверку</option>
          <option value="3">резерв</option>
          <option value="3">пригласили на собеседование</option>
          <option value="3">выходит работать</option>
        </select>
      </td>
      <td>
        <select>
          <option value="1">Lorem ipsum dolor sit amet.</option>
          <option value="2">Lorem ipsum dolor sit amet.</option>
          <option value="3">Lorem ipsum dolor sit amet.</option>
        </select>
      </td>
      <td>
        <input type="text">
      </td>
      <td> </td>
      <td><input type="checkbox" /></td>
      <td>
        <label>Резюме</label><input type="checkbox" /><br />
        <label>портфолио</label><input type="checkbox" />
      </td>
      <td>
        <input type="text">
      </td>
      <td>
        <input type="text">
      </td>
    </tr>
    <tr onclick="window.location.href='candidate.php?id=1'">
      <td>1</td>
      <td>Vestibulum quis sapien</td>
      <td>Киев</td>
      <td>middle</td>
      <td>пригласили на собеседование</td>
      <td>Lorem ipsum dolor sit amet.</td>
      <td>
        телефон:(111)111-11-11<br />
        скайп: some_skype<br />
        E-mail: <a href="mailto:#">some@mail.net</a>
      </td>
      <td>1000 uan</td>
      <td><input type="checkbox" disabled="disabled" checked="cecked" /></td>
      <td>
        <a href="#">портфолио</a><br />
        <a href="#">резюме</a><br />
      </td>
      <td>Vestibulum quis sapien</td>
      <td>Vivamus nulla magna, hendrerit ac auctor non, consequat eu quam. Cum sociis natoque penatibus et. </td>
    </tr>
    <tr onclick="window.location.href='candidate.php?id=1'">
      <td>2</td>
      <td>Vestibulum quis sapien</td>
      <td>Донецк</td>
      <td>junior</td>
      <td>пригласили на собеседование</td>
      <td>Lorem ipsum dolor sit amet.</td>
      <td>
        телефон:(111)111-11-11<br />
        скайп: some_skype<br />
        E-mail: <a href="mailto:#">some@mail.net</a>
      </td>
      <td>300 uan</td>
      <td><input type="checkbox" disabled="disabled" checked="cecked" /></td>
      <td>
        <a href="#">портфолио</a><br />
      </td>
      <td>Vestibulum quis sapien</td>
      <td> </td>
    </tr>
    <tr onclick="window.location.href='candidate.php?id=1'">
      <td>3</td>
      <td>Vestibulum quis sapien</td>
      <td>Полтава</td>
      <td>senior</td>
      <td>пригласили на собеседование</td>
      <td>Lorem ipsum dolor sit amet.</td>
      <td>
        телефон:(111)111-11-11<br />
        скайп: some_skype<br />
        E-mail: <a href="mailto:#">some@mail.net</a>
      </td>
      <td>10000 uan</td>
      <td><input type="checkbox" disabled="disabled" /></td>
      <td>
        <a href="#">резюме</a><br />
      </td>
      <td>Vestibulum quis sapien</td>
      <td> </td>
    </tr>
    <tr onclick="window.location.href='candidate.php?id=1'">
      <td>1</td>
      <td>Vestibulum quis sapien</td>
      <td>Киев</td>
      <td>middle</td>
      <td>пригласили на собеседование</td>
      <td>Lorem ipsum dolor sit amet.</td>
      <td>
        телефон:(111)111-11-11<br />
        скайп: some_skype<br />
        E-mail: <a href="mailto:#">some@mail.net</a>
      </td>
      <td>1000 uan</td>
      <td><input type="checkbox" disabled="disabled" /></td>
      <td>
        <a href="#">портфолио</a><br />
        <a href="#">резюме</a><br />
      </td>
      <td>Vestibulum quis sapien</td>
      <td> </td>
    </tr>
    <tr onclick="window.location.href='candidate.php?id=1'">
      <td>5</td>
      <td>Vestibulum quis sapien</td>
      <td>Киев</td>
      <td>middle</td>
      <td>пригласили на собеседование</td>
      <td>Lorem ipsum dolor sit amet.</td>
      <td>
        телефон:(111)111-11-11<br />
        скайп: some_skype<br />
        E-mail: <a href="mailto:#">some@mail.net</a>
      </td>
      <td>1000 uan</td>
      <td><input type="checkbox" disabled="disabled" checked="cecked" /></td>
      <td>
        <a href="#">портфолио</a><br />
        <a href="#">резюме</a><br />
      </td>
      <td>Vestibulum quis sapien</td>
      <td> </td>
    </tr>
  </table>
</form>
<?
Block::show("Footer");
?>