Вопросы: 
1. Пришлите пример (если есть) шаблона стандартного компонента ( news/ catalog) разработанного вами

2. Пришлите пример (если есть) доработанного или модернизированного стандартного компонента битрикс

3. Пришлите пример (если есть) компонента битрикс, который вы создали "с нуля".

4. Пришлите пример (если есть) шаблона сайта битрикс, который вы разработали
5. У вас стоит задача вывести на сайт врачей клиники. Как бы вы реализовали эту задачу?

Ответы: 
1. news/articles 
2. main.feedback (произвольные обязательные и не обязательные поля)
3. ip.line3 (определение местоположения по ip), product.delivery (расчет доставки в товаре на основе местоположения) 
4. Шаринг целого шаблона сайта предыдущий заказчик не одобрил 
5. Если врачи клиники - это пользователи, то простой компонент на основе CUser::GetList или Bitrix\Main\UserTable; если врачи элементы инфоблока, то news.list 

Доп 
* classes - классы для обновления цен с удаленного сайтов поставщиков 
