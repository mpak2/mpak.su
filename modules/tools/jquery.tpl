<head>
	<script type="text/javascript" src="/include/jquery/jquery.js"></script>
	<style>
	    body{background:url('/tags/images/fade.png'); font:10pt Arial,sans-serif;}
        #slided{overflow:visible; width:1250px; padding:15px; position:relative;}
        .itArea{float:left; margin:5px; padding-bottom:20px; padding-right:10px; background-color:#555; border-radius:5px;}
        .itArea h2{font-size:15pt; margin:15px 15px -3px 15px; color:#fff}
        .itArea a{color:#444; text-decoration:none;}
        .itArea a:hover{text-decoration:underline;}
        .col{float:left; margin:0 15px;}
        .col h4{font-size:10pt; margin:13px 0 0 0; color:#444}
        .it{padding-top:2px; position:relative; position:relative;}
        .tx{display:none; position:absolute; left:0; padding:5px; width:300px; background-color:#f5f5dc; font:8pt Arial,sans-serif; z-index:6; border-radius:5px;}
        
        #selectors{background-color:#dbcd8b; padding-bottom:63px}
        #events{background-color:#b5cf84; padding-right:25px;}
        #effects{background-color:#d48bb2; padding-right:21px; padding-bottom:110px;}
        #manipulation{background-color:#8faed4;}
        #iajax{background-color:#bfa4df; padding-bottom:63px;}
        #icore{background-color:#e19a98; padding-bottom:81px;}
        #traversing{background-color:#e0e184; padding-bottom:121px;}

        #selectors{background-color:#e3dbba; padding-bottom:63px}
        #events{background-color:#d0dce3; padding-right:25px;}
        #effects{background-color:#d9d5dd; padding-right:21px; padding-bottom:110px;}
        #manipulation{background-color:#e4bcc8;}
        #icore{background-color:#c2c2c2; padding-bottom:81px;}
        #iajax{background-color:#d1d7b8; padding-bottom:63px;}
        #traversing{background-color:#e8d3e0; padding-bottom:121px;}

		#txts{display:none; position:absolute; padding:5px; width:300px; background-color:#f5f5dc; font:8pt Arial,sans-serif; z-index:6; border-radius:5px;}

        .clear{clear:both}
	</style>
</head>
	<div id="slided">
	    <a href="/" style="position:absolute; top:-2px; left:22px; color:#6f5d49; font:10pt Arial,sans-serif;">mpak.su</a>
	    <div id="selectors" class="itArea">
	        <h2>Селекторы</h2>
	        <div class="col">
	            <h4>Базовые</h4>
	            <div class="it">
		            <a href="javascript:gogo('Селектор по идентификатору');">#id</a>
					<span class="tx">Ищет элемент страницы с заданным идентификатором.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Селектор по классу');">.class</a>
					<span class="tx">Ищет элементы страницы, принадлежащие заданному классу.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Селектор по имени тега');">tagName</a>

					<span class="tx">Ищет элементы страницы по имени тега.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Селектор всех элементов');">*</a>
					<span class="tx">Ищет все элементы.</span>
	            </div>

	            <h4>Взаимодействия</h4>

	            <div class="it">
		            <a href="javascript:gogo('Групповые селекторы');">first, second, ...</a>
					<span class="tx">Ищет элементы страницы, которые удовлетворяют хотя бы одному из селекторов <i>first</i>, <i>second</i>, ...</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Нисходящие селекторы');">outer inner</a>

					<span class="tx">Ищет элементы, удовлетворяющие селектору <i>inner</i>, внутри элементов, удовлетворяющих <i>outer</i>.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Дочерние селекторы');">parent > child</a>
					<span class="tx">Ищет элементы, удовлетворяющие селектору <i>child</i>, которые при этом являются <b>прямыми</b> потомками элементов, удовлетворяющих <i>parent</i>.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Смежные селекторы');">prev + next</a>
					<span class="tx">Ищет элементы, удовлетворяющие селектору <i>next</i>, которые следуют <b>непосредственно</b> за элементами, удовлетворяющими <i>prev</i>.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Следующие селекторы');">prev ~ next</a>
					<span class="tx">Ищет элементы, удовлетворяющие селектору <i>next</i>, которые следуют за элементами, удовлетворяющими <i>prev</i>.</span>
	            </div>

	            <h4>Селекторы по атрибутам</h4>
	            <div class="it">
		            <a href="javascript:gogo('Селектор по наличию атрибута');">[name]</a>
					<span class="tx">Ищет элементы, содержащие атрибут <i>name</i></span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Селектор по значению атрибута');">[name = value]</a>

					<span class="tx">Ищет элементы, у которых значение атрибута <i>name</i> совпадает с <i>value</i>.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Селектор по несовпадению с атрибутом');">[name != value]</a>
					<span class="tx">Ищет элементы, у которых значение атрибута <i>name</i> не совпадает с <i>value</i>.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Селектор по началу атрибута');">[name ^= value]</a>
					<span class="tx">Ищет элементы, у которых значение атрибута <i>name</i> начинается с <i>value</i>.</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Селектор по концу атрибута');">[name $= value]</a>
					<span class="tx">Ищет элементы, у которых значение атрибута <i>name</i> заканчивается на <i>value</i>.</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Селектор по подстроке в атрибуте');">[name *= value]</a>
					<span class="tx">Ищет элементы, у которых значение атрибута <i>name</i> содержит подстроку <i>value</i>.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Селектор по слову в атрибуте');">[name ~= value]</a>

					<span class="tx">Ищет элементы, у которых значение атрибута <i>name</i> содержит слово <i>value</i>.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Селектор по префиксу атрибута');">[name |= value]</a>
					<span class="tx">Ищет элементы, у которых значение атрибута <i>name</i> имеют префикс <i>value</i> (равен value или имеет вид: "value-*").</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Селектор по нескольким атрибутам');">[first][second][...</a>
					<span class="tx">Ищет элементы, соответствующие всем заданным условиям на атрибуты одновременно.</span>
	            </div>
	        </div>

	        <div class="col">

	            <h4>Простые фильтры</h4>
	            <div class="it">
		            <a href="javascript:gogo('Первый найденный элемент');">:first</a>
					<span class="tx">Оставляет первый из найденных элементов.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Последний найденный элемент');">:last</a>

					<span class="tx">Оставляет последний найденных элементов.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Исключение из найденных элементов');">:not(selector)</a>
					<span class="tx">Исключает из найденных элементов те, которые удовлетворяют селектору <i>selector</i>.</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Четные элементы');">:even</a>
					<span class="tx">Оставляет только четные найденные элементы.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Нечетные элементы');">:odd</a>
					<span class="tx">Оставляет только нечетные найденные элементы.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Элементы с индексом после n');">:gt(n)</a>
					<span class="tx">Оставляет только те найденные элементы, чей индекс превосходит n.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Элементы с индексом до n');">:lt(n)</a>

					<span class="tx">Оставляет только те найденные элементы, чей индекс не превосходит n.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Заголовки');">:header</a>
					<span class="tx">Оставляет только те найденные элементы, которые являются заголовками (с тегами h1, h2 и.т.д.).</span>
	            </div>
	            <div class="it notReady">

		            <a href="javascript:gogo('Анимированные элементы');">:animated</a>
					<span class="tx">Оставляет те найденные элементы, которые в данный момент задействованы в анимации.</span>
	            </div>
	            
	            <h4>Фильтры по содержимому</h4>
	            <div class="it">
		            <a href="javascript:gogo('Селектор по тексту');">:contains(text)</a>
					<span class="tx">Оставляет те найденные элементы, которые содержат заданный текст.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Селектор отсутствия содержимого');">:empty</a>
					<span class="tx">Оставляет те найденные элементы, у которых нет содержимого (текста и других элементов).</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Селектор наличия конретных элементов');">:has(selector)</a>

					<span class="tx">Оставляет те найденные элементы, которые содержат внутри хотя бы один элемент, удовлетворяющий селектору <i>selector</i>.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Селектор наличия содержимого');">:parent</a>
					<span class="tx">Оставляет непустые элементы из всех найденных.</span>
	            </div>

           		<h4>Фильтры дочерних элементов</h4>
	            <div class="it">
		            <a href="javascript:gogo('Первые элементы в своих родителях');">:first-child</a>
					<span class="tx">Оставляет те найденные элементы, которые расположены первыми в своих родительских элементах.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Последние элементы в своих родителях');">:last-child</a>

					<span class="tx">Оставляет те найденные элементы, которые расположены последними в своих родительских элементах.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Заданные элементы в своих родителях');">:nth-child(conditions)</a>
					<span class="tx">Оставляет те найденные элементы, которые расположены определенным образом в родительских элементах (четные, нечетные, идущие под заданным номером).</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Единственные элементы в своих родителях');">:only-child</a>
					<span class="tx">Оставляет те найденные элементы, которые являются единственными потомками в своих родительских элементах.</span>
	            </div>
			</div>

	        <div class="col">
	            <h4>Фильтры элементов форм</h4>
	            <div class="it">

		            <a href="javascript:gogo('Только элементы формы');">:input</a>
					<span class="tx">Оставляет только те найденные элементы, которые являются элементами формы (с тегами input, textarea или button).</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Только кнопки');">:button</a>
					<span class="tx">Оставляет только те найденные элементы, которые являются кнопками (с тегом button или типом button).</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Только переключатели');">:radio</a>
					<span class="tx">Оставляет только те найденные элементы, которые являются флажками.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Только текстовые поля');">:text</a>
					<span class="tx">Оставляет только те найденные элементы, которые являются текстовыми полями.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Только поля ввода пароля');">:password</a>
					<span class="tx">Оставляет только те найденные элементы, которые являются полями ввода пароля.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Только поля загрузки файлов');">:file</a>

					<span class="tx">Оставляет только те найденные элементы, которые являются полями загрузки файлов.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Только кнопки отправки формы');">:submit</a>
					<span class="tx">Оставляет только те найденные элементы, которые являются кнопками отправки формы.</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Только кнопки очистки формы');">:reset</a>
					<span class="tx">Оставляет только те найденные элементы, которые являются кнопками очистки формы.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Только изображения для отправки формы');">:image</a>
					<span class="tx">Оставляет только те найденные элементы, которые являются изображениями для отправки формы (input типа image).</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Только выделенные элементы');">:selected</a>
					<span class="tx">Оставляет найденные элементы со статусом selected. Это могут быть элементы типа <tt>&lt;option&gt;</tt>.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Только выбранные элементы');">:checked</a>

					<span class="tx">Оставляет найденные элементы со статусом checked. Это могут быть элементы типа <tt>&lt;checkbox&gt;</tt> или <tt>&lt;radio&gt;</tt>.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Только активные элементы формы');">:enabled</a>
					<span class="tx">Из всех выбранных элементов, оставляет только активные элементы формы (со статусом enabled).</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Только неактивные элементы формы');">:disabled</a>
					<span class="tx">Из всех выбранных элементов, оставляет только неактивные элементы формы (со статусом disabled).</span>
	            </div>
			</div>
  		</div>
		
		<div id="events" class="itArea">

			<h2>События</h2>
	        <div class="col">
	            <h4>Базовые</h4>
	            <div class="it">
		            <a href="javascript:gogo('Установка обработчика событий');">.bind()</a>
					<span class="tx">Устанавливает обработчик события на выбранные элементы страницы.</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Установка умного обработчика событий');">.live()</a>
					<span class="tx">Устанавливает обработчик события на выбранные элементы страницы. Обработчик сработает и на элементах, появившихся после его установки.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Установка обработчика с заданным контекстом');">.delegate()</a>
					<span class="tx">Устанавливает обработчик события на выбранные элементы страницы. Элементы выбираются с помощью уточняющего селектора. Обработчик будет действовать и на элементах, появившихся после его установки.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Установка разового обработчика событий');">.one()</a>
					<span class="tx">Устанавливает обработчик события на выбранные элементы страницы, который сработает только по одному разу, на каждом из элементов.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Удаление обработчика событий');">.unbind()</a>

					<span class="tx">Удаляет обработчик событий у выбранных элементов страницы.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Удаление умного обработчика событий');">.die()</a>

					<span class="tx">Удаляет обработчик событий, который был установлен с помощью live().</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Удаление обработчика с заданным контекстом');">.undelegate()</a>
					<span class="tx">Удаляет обработчик событий, который был установлен с помощью delegate().</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Вызов события');">.trigger()</a>
					<span class="tx">Выполняет указанное событие и запускает его обработчик.</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Вызов обработчика');">.triggerHandler()</a>
					<span class="tx">Запускает обработчик указанного события, без выполнения самого события.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Изменение контекста функции');">jQuery.proxy()</a>
					<span class="tx">По заданной функции, создает другую, внутри которой переменная <tt>this</tt> будет равна заданному значению.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Объект event');">event</a>
					<span class="tx">Объект, содержащий данные о текущем событии. Передается всем обработчикам событий.</span>
	            </div>

	            <h4>Мышь</h4>
	            <div class="it">

		            <a href="javascript:gogo('Обработчик или источник события click');">.click()</a>
					<span class="tx">Устанавливает обработчик "клика" мышью по элементу, либо, запускает это событие.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик или источник события dblclick');">.dblclick()</a>
					<span class="tx">Устанавливает обработчик двойного "клика" мышью по элементу, либо, запускает это событие.</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Обработчик события hover');">.hover()</a>
					<span class="tx">Устанавливает обработчик двух событий: появления/исчезновения курсора над элементом.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик или источник события mousedown');">.mousedown()</a>
					<span class="tx">Устанавливает обработчик нажатия кнопки мыши, либо, запускает это событие.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик или источник события mouseup');">.mouseup()</a>
					<span class="tx">Устанавливает обработчик поднятия кнопки мыши, либо, запускает это событие.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик или источник появления курсора на элементе');">.mouseenter()</a>

					<span class="tx">Устанавливает обработчик появления курсора в области элемента, либо, запускает это событие. Появление этого события, отработано лучше, чем стандартного mouseover.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик или источник выхода курсора из элемента');">.mouseleave()</a>
					<span class="tx">Устанавливает обработчик выхода курсора из области элемента, либо, запускает это событие. Появление этого события, отработано лучше, чем стандартного mouseout.</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Обработчик или источник события mousemove');">.mousemove()</a>
					<span class="tx">Устанавливает обработчик движения курсора в области элемента, либо, запускает это событие.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик или источник события mouseout');">.mouseout()</a>
					<span class="tx">Устанавливает обработчик выхода курсора из области элемента, либо, запускает это событие.</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Обработчик или источник события mouseover');">.mouseover()</a>
					<span class="tx">Устанавливает обработчик появления курсора в области элемента, либо, запускает это событие.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Поочередное выполнение функций');">.toggle()</a>
					<span class="tx">Поочередно выполняет одну из двух или более заданных функций, в ответ на "клик" по элементу.</span>

	            </div>
			</div>
			
	        <div class="col">
	            <h4>Клавиатура</h4>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик или источник события keydown');">.keydown()</a>
					<span class="tx">Устанавливает обработчик перехода клавиши клавиатуры в нажатое состояние, либо, запускает это событие.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик или источник события keyup');">.keyup()</a>
					<span class="tx">Устанавливает обработчик возвращение клавиши клавиатуры в ненажатое состояние, либо, запускает это событие.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик события keypress');">.keypress()</a>

					<span class="tx">Устанавливает обработчик ввода символа с клавиатуры, либо, запускает это событие.</span>
	            </div>

	            <h4>Элементы формы</h4>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик или источник события focus');">.focus()</a>
					<span class="tx">Устанавливает обработчик получения фокуса, либо, запускает это событие.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик или источник события blur');">.blur()</a>
					<span class="tx">Устанавливает обработчик потери фокуса, либо, запускает это событие.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик события focusin');">.focusin()</a>

					<span class="tx">Устанавливает обработчик получения фокуса самим элементом или одним из его дочерних.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик события focusout');">.focusout()</a>
					<span class="tx">Устанавливает обработчик получения фокуса самим элементом или одним из его дочерних.</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Обработчик или источник события select');">.select()</a>
					<span class="tx">Устанавливает обработчик выделения текста, либо, запускает это событие.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик или источник события submit');">.submit()</a>
					<span class="tx">Устанавливает обработчик отправки формы, либо, запускает это событие.</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Обработчик или источник события change');">.change()</a>
					<span class="tx">Устанавливает обработчик изменения элемента формы, либо, запускает это событие.</span>
	            </div>

	            <h4>Загрузка страницы</h4>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик готовности дерева DOM');">.ready()</a>

					<span class="tx">Устанавливает обработчик готовности дерева DOM.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик события load');">.load()</a>
					<span class="tx">Устанавливает обработчик завершения загрузки элемента.</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Обработчик события unload');">.unload()</a>
					<span class="tx">Устанавливает обработчик ухода со страницы (при переходе по ссылке, закрытии браузера и.т.д.).</span>
	            </div>

	            <h4>События браузера</h4>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик события error');">.error()</a>

					<span class="tx">Устанавливает обработчик ошибки при загрузке элементов (например отсутствие необходимой картинки на сервере).</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработчик или источник события resize');">.resize()</a>
					<span class="tx">Устанавливает обработчик изменения размеров окна браузера, либо, запускает это событие.</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Обработчик или источник события scroll');">.scroll()</a>
					<span class="tx">Устанавливает обработчик "прокрутки" элементов документа, либо, запускает это событие.</span>
	            </div>
			</div>
		</div>
		
	    <div id="effects" class="itArea">
	        <h2>Эффекты</h2>

	        <div class="col">
	            <h4>Управление анимацией</h4>
	            <div class="it">
		            <a href="javascript:gogo('Выполнение пользовательской анимации');">.animate()</a>
					<span class="tx">Выполняет анимацию, которая была создана пользователем.</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Очередь предстоящих функций');">.queue()</a>
					<span class="tx">Предоставляет/изменяет (в зависимости от параметров) очередь функций.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Очищение очереди функций');">.clearQueue()</a>
					<span class="tx">Очищает очередь функций.</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Запуск следующей функции в очереди');">.dequeue()</a>
					<span class="tx">Начинает выполнение следующей функции в очереди.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Прекращение текущей анимации');">.stop()</a>
					<span class="tx">Останавливает выполнение текущей анимации.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Временное приостановление дальнейших анимаций');">.delay()</a>
					<span class="tx">Приостанавливает выполнение следующих анимаций на заданное время.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Поочередное выполнение функций');">.toggle()</a>

					<span class="tx">Поочередно выполняет вызов одной из нескольких заданных функций.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Отмена выполнения всех анимаций');">jQuery.fx.off</a>
					<span class="tx">Отменяет выполнение всех анимаций.</span>
	            </div>

	            <h4>Готовые анимации</h4>

	            <div class="it">
		            <a href="javascript:gogo('Появление и иcчезновение элементов');">.hide()</a>
					<span class="tx">Скрывает элементы на странице (за счет плавного изменения его размера и прозрачности).</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Появление и иcчезновение элементов');">.show()</a>
					<span class="tx">Показывает элементы на странице (за счет плавного изменения его размера и прозрачности).</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Разворачивание и сворачивание элементов');">.slideUp()</a>
					<span class="tx">Сворачивает элементы на странице (за счет плавного изменения высоты элементов).</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Разворачивание и сворачивание элементов');">.slideDown()</a>

					<span class="tx">Разворачивает элементы на странице (за счет плавного изменения высоты элементов)</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Поочередное разворачивание и сворачивание элементов');">.slideToggle()</a>
					<span class="tx">Поочередно разворачивает/сворачивает элементы на странице, как это делают .slideUp() и .slideDown().</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Появление и иcчезновение элементов за счет прозрачности');">.fadeIn()</a>
					<span class="tx">Скрывает элементы на странице за счет плавного изменения прозрачности.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Появление и иcчезновение элементов за счет прозрачности');">.fadeOut()</a>
					<span class="tx">Показывает элементы на странице за счет плавного изменения прозрачности.</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Установка заданной прозрачности у элемента');">.fadeTo()</a>
					<span class="tx">Плавно изменяет прозрачность элементов.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Поочередное появление и скрытие элементов');">.fadeToggle()</a>
					<span class="tx">Поочередно скрывает/показывает элементы на странице, как это делают .fadeIn() и .fadeOut().</span>

	            </div>
			</div>
		</div>
		
		<div class="clear" />
		
	    <div id="manipulation" class="itArea">
	        <h2>Манипуляции</h2>
	        <div class="col">
	            <h4>Атрибуты</h4>

	            <div class="it">
		            <a href="javascript:gogo('Работа с атрибутами');">.attr()</a>
					<span class="tx">Позволяет получать и изменять значение атрибутов у элементов страницы</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Удаление атрибута');">.removeAttr()</a>
					<span class="tx">Удаляет атрибуты у выбранных элементов страницы</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Добавление класса');">.addClass()</a>
					<span class="tx">Добавляет класс(ы) выбранным элементам страницы</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Удаление класса');">.removeClass()</a>

					<span class="tx">Удаляет классы у выбранных элементов страницы</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Варьировние наличия класса');">.toggleClass()</a>
					<span class="tx">Изменяет наличие класса у выбранных элементов страницы на противоположное (добавляет/удаляет)</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Наличие класса');">.hasClass()</a>
					<span class="tx">Проверяет наличие заданного класса хотя бы у одного из выбранных элементов</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Работа с атрибутом value');">.val()</a>
					<span class="tx">Позволяет получать и изменять значение атрибута <i>value</i> у элементов страницы</span>

	            </div>

	            <h4>Стили и параметры</h4>
	            <div class="it">
		            <a href="javascript:gogo('Работа с CSS');">.css()</a>
					<span class="tx">Позволяет получать и изменять css-значения у элементов страницы</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Работа с высотой элемента');">.height()</a><br />
					<span class="tx">Позволяет получать и изменять значения высоты элемента (без учета отступов и толщины рамки)</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Работа с высотой элемента');">.outerHeight()</a><br />
					<span class="tx">Позволяет получить значения высоты элемента (с учетом внутренних отступов, рамки (border-width) и при необходимости внешних отступов (margin))</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Работа с высотой элемента');">.innerHeight()</a><br />
					<span class="tx">Позволяет получить значения высоты элемента (с учетом размера внутренних отступов (padding))</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Работа с шириной элемента');">.width()</a><br />
					<span class="tx">Позволяет получать и изменять значения ширины элемента (без учета отступов и толщины рамки)</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Работа с шириной элемента');">.outerWidth()</a><br />
					<span class="tx">Позволяет получить значения ширины элемента (с учетом внутренних отступов, рамки (border-width) и при необходимости внешних отступов (margin))</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Работа с шириной элемента');">.innerWidth()</a><br />

					<span class="tx">Позволяет получить значения ширины элемента (с учетом размера внутренних отступов (padding))</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Позиция элемента');">.offset()</a>
					<span class="tx">Позволяет получать и изменять позицию элемента, относительно начала страницы</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Позиция элемента');">.position()</a>
					<span class="tx">Позволяет получать и изменять позицию элемента, относительно ближайшего родственника</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Спозиционированные родительские элементы');">.offsetParent()</a>
					<span class="tx">Возвращает ближайшего предка c позиционированием, отличным от static (позиционирование по умолчанию)</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Работа со скроллингом элементов');">.scrollTop()</a>
					<span class="tx">Позволяет получать и изменять значения вертикальной прокрутки элементов на странице</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Работа со скроллингом элементов');">.scrollLeft()</a>
					<span class="tx">Позволяет получать и изменять значения горизонтальной прокрутки элементов на странице</span>

	            </div>

	            <h4>Клонирование элементов</h4>
	            <div class="it">
		            <a href="javascript:gogo('Клонирование элементов');">.clone()</a>
					<span class="tx">Делает копию выбранных элементов страницы..</span>
	            </div>
	        </div>

	        <div class="col">
	            <h4>Добавление содержимого</h4>
	            <div class="it">
		            <a href="javascript:gogo('Работа с html-содержимым элемента');">.html()</a>
					<span class="tx">Позволяет получать и изменять html-содержимое выбранных элементов на странице</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Работа с текстовым содержимым элемента');">.text()</a>
					<span class="tx">Позволяет получать и изменять текст внутри выбранных элементов на странице</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Добавление содержимого в конец элементов');">.append()</a>
					<span class="tx">Добавляет заданное содержимое в конец элементов на странице</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Добавление содержимого в конец элементов');">.appendTo()</a>
					<span class="tx">Добавляет заданное содержимое в конец элементов на странице</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Добавление содержимого в начало элементов');">.prepend()</a>
					<span class="tx">Добавляет заданное содержимое в начало элементов на странице</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Добавление содержимого в начало элементов');">.prependTo()</a>
					<span class="tx">Добавляет заданное содержимое в начало элементов на странице</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Добавление содержимого после элементов');">.after()</a>

					<span class="tx">Добавляет заданное содержимое после выбранных элементов на странице</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Добавление содержимого после элементов');">.insertAfter()</a>
					<span class="tx">Добавляет заданное содержимое после выбранных элементов на странице</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Добавление содержимого перед элементами');">.before()</a>
					<span class="tx">Добавляет заданное содержимое перед выбранными элементами на странице</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Добавление содержимого перед элементами');">.insertBefore()</a>
					<span class="tx">Добавляет заданное содержимое перед выбранными элементами на странице</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Обертывание элементов страницы');">.wrap()</a>
					<span class="tx">Окружает элементы на странице заданными html-элементами</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обертывание элементов страницы');">.wrapAll()</a>
					<span class="tx">Окружает элементы на странице заданными html-элементами</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обертывание содержимого элементов страницы');">.wrapInner()</a>
					<span class="tx">Окружает содержимое выбранных элементов заданными html-элементами</span>
	            </div>

	            <h4>Удаление содержимого</h4>
	            <div class="it">

		            <a href="javascript:gogo('Удаление объектов');">.remove()</a>
					<span class="tx">Удаляет выбранные элементы на странице.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Удаление объектов');">.detach()</a>
					<span class="tx">Удаляет выбранные элементы на странице. Удаленные элементы могут быть восстановлены.</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Удаление содержимого у выбранных объектов');">.empty()</a>
					<span class="tx">Удаляет содержимое элементов на странице.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Удаление родительских объектов');">.unwrap()</a>
					<span class="tx">Удаляет родительские элементы, при этом их содержимое остается на месте.</span>

	            </div>

	            <h4>Замена элементов</h4>
	            <div class="it">
		            <a href="javascript:gogo('Замена элементов');">.replaceWith()</a>
					<span class="tx">Заменяет одни элементы страницы на другие (новые или уже существующие).</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Замена элементов');">.replaceAll()</a>
					<span class="tx">Заменяет одни элементы страницы на другие (новые или уже существующие).</span>
	            </div>
		 	</div>
		</div>

	    <div id="icore" class="itArea">
	        <h2>Остальное</h2>

	        <div class="col">
	            <h4>Функции ядра</h4>
	            <div class="it">
		            <a href="javascript:gogo('Функция jQuery');">jQuery() или $()</a>
					<span class="tx">Создает объект jQuery, который содержит список выбранных элементов и имеет массу методов, для работы с этими элементами.</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Предупреждение конфликтов');">$.noConflict()</a>
					<span class="tx">Освобождает переменную $, чтобы избежать конфликтов имен.</span>
	            </div>

	            <h4>Работа с данными</h4>
	            <div class="it">
		            <a href="javascript:gogo('Сохранение данных');">.data()</a>

					<span class="tx">Устанавливает/возвращяет пользовательские переменные выбранным элементам страницы.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Удаление данных');">.removeData()</a>
					<span class="tx">Удаляет пользовательские переменные у выбранных элементов.</span>
	            </div>

	            <h4>Элементы набора</h4>

	            <div class="it">
		            <a href="javascript:gogo('Элементы объекта jQuery');">.get()</a>
					<span class="tx">Возвращяет DOM-элементы, хранящиеся в объекте jQuery.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Конвертирование jQuery в массив');">.toArray()</a>
					<span class="tx">Возвращяет все DOM-элементы, хранящиеся в объекте jQuery, в виде массива.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Индекс элемента в наборе');">.index()</a>
					<span class="tx">Возвращяет индекс заданного элемента в наборе.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Размер набора');">.size()</a>

					<span class="tx">Возвращяет количество выбранных элементов.</span>
	            </div>
	            
	            <h4>Свойства</h4>
	            <div class="it">
		            <a href="javascript:gogo('Тип браузера');">$.browser</a>
					<span class="tx">Информация об используемом браузере (перед использованием этого свойства попробуйте найти интересующую вас особенность в свойстве support, эта информация будет более надежна).</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Особенности используемого браузера');">$.support</a>
					<span class="tx">Особенности используемого браузера.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Отмена выполнения всех анимаций');">$.fx.off</a>
					<span class="tx">Отменяет выполнение всех анимаций.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Длительность кадра анимаций');">$.fx.interval</a>
					<span class="tx">Размер задержки между соседними кадрами анимации.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Контекст выполнения');">.context</a>

					<span class="tx">Контекст, заданный в методе <tt>$()</tt> (<tt>jQuery()</tt>).</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Число элементов в наборе');">.length</a>
					<span class="tx">Число выбранных элементов.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Текущий селектор');">.selector</a>
					<span class="tx">Селектор, заданный в методе <tt>$()</tt> (<tt>jQuery()</tt>).</span>
	            </div>

	        </div>
		</div>
		
	    <div id="iajax" class="itArea">
	        <h2>Ajax</h2>
	        <div class="col">
	            <h4>Запросы к серверу</h4>
	            <div class="it">
		            <a href="javascript:gogo('Ajax запрос методом GET');">$.get()</a>

					<span class="tx">Производит запрос к серверу методом GET.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Ajax запрос методом POST');">$.post()</a>
					<span class="tx">Производит запрос к серверу методом POST.</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Ajax запрос HTML-данных');">.load()</a>
					<span class="tx">Производит запрос HTML-данных у сервера и помещает их в выбранные элементы страницы.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Ajax запрос JSON-данных');">$.getJSON()</a>
					<span class="tx">Производит запрос JSON-данных у сервера методом GET</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Ajax запрос js файла');">$.getScript()</a>
					<span class="tx">Производит запрос файла javascript методом GET, а затем выполняет код из полученного файла.</span>
	            </div>

	            <h4>Низкоуровневые запросы</h4>
	            <div class="it">
		            <a href="javascript:gogo('Ajax-запрос');">$.ajax()</a>

					<span class="tx">Производит асинхронный ajax-запрос с установленными параметрами.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Параметры ajax-запроса');">$.ajaxSetup()</a>
					<span class="tx">Устанавливает параметры для ajax-запроса, которые будут использоваться по умолчанию.</span>
	            </div>

	            <h4>Ajax-события</h4>

	            <div class="it">
		            <a href="javascript:gogo('Обработка отправления ajax-запроса');">.ajaxSend()</a>
					<span class="tx">Устанавливает пользовательскую функцию, которая будет вызвана при отправке ajax-запроса.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработка завершения ajax-запроса');">.ajaxComplete()</a>
					<span class="tx">Устанавливает пользовательскую функцию, которая будет вызвана при завершении ajax-запроса.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработка удачного завершения ajax-запроса');">.ajaxSuccess()</a>
					<span class="tx">Устанавливает пользовательскую функцию, которая будет вызвана при удачном завершении ajax-запроса.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработка неудачного завершения ajax-запроса');">.ajaxError()</a>

					<span class="tx">Устанавливает пользовательскую функцию, которая будет вызвана при неудачном завершении ajax-запроса.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Обработка первого ajax-запроса');">.ajaxStart()</a>
					<span class="tx">Устанавливает пользовательскую функцию, которая будет вызвана перед выполнением первого ajax-запроса.</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Обработка завершения всех ajax-запросов');">.ajaxStop()</a>
					<span class="tx">Устанавливает пользовательскую функцию, которая будет вызвана после выполнения всех запущенных ajax-запросов.</span>
	            </div>

			    <h4>Вспомогательные функции</h4>
	            <div class="it">
		            <a href="javascript:gogo('Преобразование объектов для использования в url');">$.param()</a>

					<span class="tx">Преобразует массив объектов в строку, пригодную для использования в URL.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Преобразование данных формы в строку');">.serialize()</a>
					<span class="tx">Преобразует данные формы в строку, пригодную для использования в URL.</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Преобразование данных формы в массив');">.serializeArray()</a>
					<span class="tx">Преобразует данные формы в массив объектов вида <tt>{name:"name", value:"val"}</tt></span>
	            </div>
	        </div>
		</div>

	    <div id="traversing" class="itArea">
	        <h2>Работа с набором элементов</h2>

	        <div class="col">
	            <h4>Перемещения по DOM</h4>
	            <div class="it">
		            <a href="javascript:gogo('Поиск дочерних элементов');">.children()</a>
					<span class="tx">Находит все дочерние элементы у выбранных элементов. При необходимости, можно указать селектор для фильтрации.</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Ближайший подходящий предок');">.closest()</a>
					<span class="tx">Находит ближайший, соответствующий заданному селектору элемент, из числа следующих: сам выбранный элемент, его родитель, его прародитель, и так далее, до начало дерева DOM.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Поиск элементов внутри выбранных');">.find()</a>
					<span class="tx">Находит элементы по заданному селектору, внутри выбранных элементов.</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Поиск элементов лежащих после выбранных');">.next()</a>
					<span class="tx">Находит элементы, которые лежат '''непосредственно''' после каждого из выбранных элементов.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Поиск всех элементов лежащих после выбранных');">.nextAll()</a>
					<span class="tx">Находит элементы, которые лежат после каждого из выбранных элементов.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Поиск части элементов лежащих после выбранных');">.nextUntil()</a>
					<span class="tx">Находит элементы, которые лежат после каждого из выбранных, но не дальше элемента, удовлетворяющего заданному селектору.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Спозиционированные родительские элементы');">.offsetParent()</a>

					<span class="tx">Находит ближайшего предка c позиционированием, отличным от static (позиционирование по умолчанию).</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Поиск родительского элемента');">.parent()</a>
					<span class="tx">Находит родительские элементы у всех выбранных элементов.</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Поиск всех предков');">.parents()</a>
					<span class="tx">Находит всех предков у выбранных элементов, т.е. не только прямых родителей, но и прародителей, прапрародителей и так далее, до начало дерева DOM.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Поиск почти всех предков');">.parentsUntil()</a>
					<span class="tx">Находит предков, как и <tt>.parents()</tt>, но прекращает поиск перед элементом, удовлетворяющим заданному селектору.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Поиск элементов лежащих перед выбранными');">.prev()</a>
					<span class="tx">Находит элементы, которые лежат '''непосредственно''' перед каждым из выбранных элементов.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Поиск всех элементов лежащих перед выбранными');">.prevAll()</a>

					<span class="tx">Находит элементы, которые лежат перед каждым из выбранных элементов.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Поиск части элементов лежащих перед выбранными');">.prevUntil()</a>
					<span class="tx">Находит элементы, которые лежат перед каждым из выбранных, но не дальше элемента, соответствующего заданному селектору.</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Поиск элементов лежащих на одном уровне');">.siblings()</a>
					<span class="tx">Находит все соседние элементы (под соседними понимаются элементы с общим родителем).</span>
	            </div>

	            <h4>Обход набора</h4>
	            <div class="it">
		            <a href="javascript:gogo('Вызов функции для элементов набора');">.each()</a>

					<span class="tx">Вызывает заданную функцию для каждого элемента набора.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Работа функции с элементами набора');">.map()</a>
					<span class="tx">Вызывает заданную функцию для каждого элемента набора, и в итоге создает новый набор, составленный из значений, возвращенных этой функцией.</span>
	            </div>
	    	</div>

	    	<div class="col">
	            <h4>Фильтрация набора</h4>
	            <div class="it">
		            <a href="javascript:gogo('Поиск элемента с заданным номером');">.eq()</a>
					<span class="tx">Находит элемент, идущий под заданным номером в наборе выбранных элементов.</span>
	            </div>
	            <div class="it">

		            <a href="javascript:gogo('Фильтрация выбранных элементов');">.filter()</a>
					<span class="tx">Фильтрует набор выбранных элементов с помощью заданного селектора или функции.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Поиск первого элемента в наборе');">.first()</a>
					<span class="tx">Находит первый элемент в наборе.</span>
	            </div>

	            <div class="it">
		            <a href="javascript:gogo('Поиск элементов с заданным содержимым');">.has()</a>
					<span class="tx">Фильтрует набор выбранных элементов, оставляя те, которые имеют потомков, соответствующих селектору.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Проверка наличия элемента');">.is()</a>
					<span class="tx">Проверяет, содержится ли в наборе, хотя бы один элемент удовлетворяющий заданному селектору.</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Поиск последнего элемента в наборе');">.last()</a>
					<span class="tx">Находит последний элемент в наборе.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Поиск элементов не соответствующих условиям');">.not()</a>

					<span class="tx">Находит элементы, не соответствующие заданным условиям.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Поиск элементов с индексами из заданной области');">.slice()</a>
					<span class="tx">Находит элементы с индексами из определенной области (например от 0 до 5).</span>
	            </div>

	            <h4>Другие методы</h4>

	            <div class="it">
		            <a href="javascript:gogo('Добавление элементов в набор');">.add()</a>
					<span class="tx">Добавляет заданные элементы в набор.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Добавление предыдущего набора');">.andSelf()</a>
					<span class="tx">Добавляет элементы из предыдущего набора, к текущему (речь идет об одной цепочке методов).</span>

	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Специальный поиск содержащихся элементов');">.contents()</a>
					<span class="tx">Находит все дочерние элементы у выбранных элементов. В результат, помимо элементов, включается и текст.</span>
	            </div>
	            <div class="it">
		            <a href="javascript:gogo('Предыдущий набор элементов');">.end()</a>

					<span class="tx">Возвращает предыдущий набор элементов в текущей цепочке методов.</span>
	            </div>
			</div>
		</div>
		<div class="clear" style="height:50px" />
		<div id="txts"></div>
	</div>
  <script>
    var outLink = -1;
    var sscr = {
        obj:$("#slided"),
		wH:0,
		wW:0,
		sH:0,
		sW:0,
		difW:0,
		difH:0
	};

	//изменяем функции на ссылки
	var stok = "";
	$(".it a").each(function(){
		var o = $(this);
		o.attr("href").replace(/'[^~]*'/, function(s){
		    stok = s.substring(1, s.length-1);
			return s;
		});
		o.attr({"href":"http://jquery.page2page.ru/index.php5/"+stok, "target":"_top"});
	});

	// ставим обработчики наведения и снятия курсора с сылок
  	$(".itArea a").mouseenter(function(){
  	    var n = Math.floor(Math.random()*10000);
  	    outLink = n;
  	    obj = this;
		setTimeout(function(){
			if(outLink == n)
				showMess(obj);
		}, 500);
	}).mouseleave(function(){
	    if($(this).siblings(".tx").css("display") != "none")
	        $(this).siblings(".tx").css("display","none");
        outLink = -1;
	});

	// обработчики для автоматического движения экрана
	initSscr();
	$("body").mousemove(slideInPlace);
	
	$(window).resize(initSscr);

	
	function showMess(obj)
	{
	    obj = $(obj).siblings(".tx");
		
        obj.css("display","block")
		.fadeTo(0, 0.1);

	    var h = obj.height();
	    var w = obj.width();
	    var x = obj.offset().left + obj.width();
	    var offs = 0;
	    if(x>sscr.wW)
	        offs = sscr.wW - x - 20 + "px";

		if(offs != 0)
		    obj.css({"top":-h-6+"px", left:offs})
			.fadeTo(300, 0.95);
		else
			obj.css("top", -h-6+"px")
			.fadeTo(300, 0.95);
			
		if($.browser.msie)
		{
			$("#txts").css("display","block").text(obj.text()).offset({left:obj.offset().left, top:obj.offset().top});
		}
	}
	
	function initSscr()
	{
		sscr.wH = $(window).height();
		sscr.wW = $(window).width();
		sscr.sH = $("#slided").height();
		sscr.sW = $("#slided").width();
		if(sscr.wH > sscr.sH)
		    sscr.sH = sscr.wH;
		if(sscr.wW > sscr.sW)
		    sscr.sW = sscr.wW;
		sscr.difH = sscr.sH - sscr.wH;
		sscr.difW = sscr.sW - sscr.wW;
	}
	function slideInPlace(e)
	{
		var h = -Math.floor(sscr.difH*(e.pageY/sscr.wH))+"px";
		var w = -Math.floor(sscr.difW*(e.pageX/sscr.wW))+"px";
		sscr.obj.css({top:h, left:w});
	}
  </script>