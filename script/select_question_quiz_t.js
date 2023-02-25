// seleziona tipo di domanda
$(document).ready(function() {
    $(".questiontype").on('change', function() {

        var tiposelezionato = $(this).val();
        var section = $(this).parent().parent().parent()

        if(tiposelezionato == 'radioquestion') {
            section.children('#saq').css("display", "block");
            section.children('#maq').css("display", "none");
            section.children('#oq').css("display", "none");
        }
        else if(tiposelezionato == 'checkboxquestion') {
            section.children('#saq').css("display", "none");
            section.children('#maq').css("display", "block");
            section.children('#oq').css("display", "none");
        }
        else if(tiposelezionato == 'rispaperta') {
            section.children('#saq').css("display", "none");
            section.children('#maq').css("display", "none");
            section.children('#oq').css("display", "block");
        }

    });
});

// mette tante opzioni radio quanto è il numero selezionato
$(document).ready(function() {
    $(".nanswerssaq").on('change', function() {
        var numerosaq = $(this).val();
        var section = $(this).parent().parent().parent();
        var numdomanda = section.prop("id").charAt(1);
        var vanswers = section.children('#saq').children('.vanswers');

        if (vanswers.children().length > 0){
            vanswers.empty();

            var table = document.createElement('table');
            var tr = document.createElement('tr');
            var td1 = document.createElement('td');
            var td2 = document.createElement('td');
            var td3 = document.createElement('td');
            var p = document.createElement('p');
            p.innerHTML='Correct answer';
            td2.appendChild(p);
            tr.appendChild(td1);
            tr.appendChild(td2);
            tr.appendChild(td3);
            table.appendChild(tr);

            for (var i = 0; i < numerosaq; i++){
                var tr = document.createElement('tr');

                var td1 = document.createElement('td');
                var td2 = document.createElement('td');
                var td3 = document.createElement('td');

                var inputanswer = document.createElement('input');
                inputanswer.type = "text";
                inputanswer.className = "textarea";
                inputanswer.placeholder = "Enter the " + (i+1) + getOrdinal(i+1) + " answer";
                inputanswer.name = "answer";
                inputanswer.id = "answer"+(i+1);
                inputanswer.required = true;

                var inputradio = document.createElement('input');
                inputradio.type = "radio";
                inputradio.id = "check"+(i+1);
                inputradio.className = "radioscelte";
                inputradio.name = "answergroup"+numdomanda;

                var inputwrong = document.createElement('input');
                inputwrong.type = "text";
                inputwrong.className = "textarea";
                inputwrong.placeholder = "This answer is wrong because...";
                inputwrong.name = "explain";
                inputwrong.id = "explain"+(i+1);
                inputwrong.required = true;

                td1.appendChild(inputanswer);
                td2.appendChild(inputradio);
                td3.appendChild(inputwrong);

                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);

                table.appendChild(tr);
            }

            vanswers.append(table);
        }
        else{
            var table = document.createElement('table');
            var tr = document.createElement('tr');
            var td1 = document.createElement('td');
            var td2 = document.createElement('td');
            var td3 = document.createElement('td');
            var p = document.createElement('p');
            p.innerHTML='Correct answer';
            td2.appendChild(p);
            tr.appendChild(td1);
            tr.appendChild(td2);
            tr.appendChild(td3);
            table.appendChild(tr);

            for (var i = 0; i < numerosaq; i++){
                var tr = document.createElement('tr');

                var td1 = document.createElement('td');
                var td2 = document.createElement('td');
                var td3 = document.createElement('td');

                var inputanswer = document.createElement('input');
                inputanswer.type = "text";
                inputanswer.className = "textarea";
                inputanswer.placeholder = "Enter the " + (i+1) + getOrdinal(i+1) + " answer";
                inputanswer.name = "answer";
                inputanswer.id = "answer"+(i+1);
                inputanswer.required = true;

                var inputradio = document.createElement('input');
                inputradio.type = "radio";
                inputradio.id = "check"+(i+1);
                inputradio.className = "radioscelte";
                inputradio.name = "answergroup"+numdomanda;

                var inputwrong = document.createElement('input');
                inputwrong.type = "text";
                inputwrong.className = "textarea";
                inputwrong.placeholder = "This answer is wrong because...";
                inputwrong.name = "explain";
                inputwrong.id = "explain"+(i+1);
                inputwrong.required = true;

                td1.appendChild(inputanswer);
                td2.appendChild(inputradio);
                td3.appendChild(inputwrong);

                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);

                table.appendChild(tr);
            }

            vanswers.append(table);
        }
    });
});


// mette tante opzioni check quanto è il numero selezionato
$(document).ready(function() {
    $(".nanswersmaq").on('change', function() {
        var numeromaq = $(this).val();
        var section = $(this).parent().parent().parent();
        var numdomanda = section.prop("id").charAt(1);
        var vanswers = section.children('#maq').children('.vanswers');

        if (vanswers.children().length > 0){
            vanswers.empty();

            var table = document.createElement('table');
            var tr = document.createElement('tr');
            var td1 = document.createElement('td');
            var td2 = document.createElement('td');
            var td3 = document.createElement('td');
            var p = document.createElement('p');
            p.innerHTML='Correct answer';
            td2.appendChild(p);
            tr.appendChild(td1);
            tr.appendChild(td2);
            tr.appendChild(td3);
            table.appendChild(tr);

            for (var i = 0; i < numeromaq; i++){
                var tr = document.createElement('tr');

                var td1 = document.createElement('td');
                var td2 = document.createElement('td');
                var td3 = document.createElement('td');

                var inputanswer = document.createElement('input');
                inputanswer.type = "text";
                inputanswer.className = "textarea";
                inputanswer.placeholder = "Enter the " + (i+1) + getOrdinal(i+1) + " answer";
                inputanswer.name = "answer";
                inputanswer.id = "answer"+(i+1);
                inputanswer.required = true;

                var inputcheck = document.createElement('input');
                inputcheck.type = "checkbox";
                inputcheck.id = "check"+(i+1);
                inputcheck.className = "checkscelte";
                inputcheck.name = "answergroup"+numdomanda;


                var inputwrong = document.createElement('input');
                inputwrong.type = "text";
                inputwrong.className = "textarea";
                inputwrong.placeholder = "This answer is wrong because...";
                inputwrong.name = "explain";
                inputwrong.id = "explain"+(i+1);
                inputwrong.required = true;

                td1.appendChild(inputanswer);
                td2.appendChild(inputcheck);
                td3.appendChild(inputwrong);

                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);

                table.appendChild(tr);
            }

            vanswers.append(table);

        }
        else{
            var table = document.createElement('table');
            var tr = document.createElement('tr');
            var td1 = document.createElement('td');
            var td2 = document.createElement('td');
            var td3 = document.createElement('td');
            var p = document.createElement('p');
            p.innerHTML='Correct answer';
            td2.appendChild(p);
            tr.appendChild(td1);
            tr.appendChild(td2);
            tr.appendChild(td3);
            table.appendChild(tr);

            for (var i = 0; i < numeromaq; i++){
                var tr = document.createElement('tr');

                var td1 = document.createElement('td');
                var td2 = document.createElement('td');
                var td3 = document.createElement('td');

                var inputanswer = document.createElement('input');
                inputanswer.type = "text";
                inputanswer.className = "textarea";
                inputanswer.placeholder = "Enter the " + (i+1) + getOrdinal(i+1) + " answer";
                inputanswer.name = "answer";
                inputanswer.id = "answer"+(i+1);
                inputanswer.required = true;

                var inputcheck = document.createElement('input');
                inputcheck.type = "checkbox";
                inputcheck.id = "check"+(i+1);
                inputcheck.className = "checkscelte";
                inputcheck.name = "answergroup"+numdomanda;

                var inputwrong = document.createElement('input');
                inputwrong.type = "text";
                inputwrong.className = "textarea";
                inputwrong.placeholder = "This answer is wrong because...";
                inputwrong.name = "explain";
                inputwrong.id = "explain"+(i+1);
                inputwrong.required = true;

                td1.appendChild(inputanswer);
                td2.appendChild(inputcheck);
                td3.appendChild(inputwrong);

                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);

                table.appendChild(tr);
            }

            vanswers.append(table);
        }

    });
});

// aggiunge pedice per la stampa dei numeri ordinali
function getOrdinal(n) {
    let ord = 'th';

    if (n % 10 == 1 && n % 100 != 11)
    {
      ord = 'st';
    }
    else if (n % 10 == 2 && n % 100 != 12)
    {
      ord = 'nd';
    }
    else if (n % 10 == 3 && n % 100 != 13)
    {
      ord = 'rd';
    }

    return ord;
}

// aggiunge una nuova domanda
$(document).ready(function() {
    $("#addQuestion").on('click', function() {

        var sections = document.querySelectorAll("section[id^='d']");
        //.prop("id").charAt(1);
        console.log(sections.last());
    });
});