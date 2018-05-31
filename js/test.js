
    var curr=1;
    var question;
    function next(){
        checked(curr);
        document.getElementById('question'+curr).setAttribute('style','display:none');
        document.getElementById('answers'+curr).setAttribute('style','display:none');
        curr++;
        document.getElementById('question'+curr).setAttribute('style','display:block');
        document.getElementById('answers'+curr).setAttribute('style','display:block');
        document.getElementById('prev').setAttribute('style','display:block');
        if(curr==numOfQuestions){
            document.getElementById('next').setAttribute('style','display:none');

        }

        checked(curr);


    }


    function prev(){
        checked(curr);
        document.getElementById('question'+curr).setAttribute('style','display:none');
        document.getElementById('answers'+curr).setAttribute('style','display:none');
        curr--;
        document.getElementById('question'+curr).setAttribute('style','display:block');
        document.getElementById('answers'+curr).setAttribute('style','display:block');
        if(curr==1){
            document.getElementById('prev').setAttribute('style','display:none');
            document.getElementById('next').setAttribute('style','display:block');
        }
        if(curr!=numOfQuestions){
            document.getElementById('next').setAttribute('style','display:block');

        }

        checked(curr);
    }
    

    $(document).keydown(function(e) {
      if(e.keyCode == 37) { // left
        if(curr!=numOfQuestions)
            next();
        
      }
      else if(e.keyCode == 39) { // right
        if(curr!=1)
            prev();
      }
else if(e.keyCode == 27) { // esc
        window.location = "../index.php";
      }

    });

    function questionBar(){
        question=1;       
        if (numOfQuestions <= 20){
            for(var i=0;i<Math.sqrt(numOfQuestions);i++){
                var div=document.createElement('div');
                div.setAttribute('class','qRow');
                div.setAttribute('id','qRow'+i);
                document.getElementById('qNumbers').appendChild(div);
                for(var j=1;j<=Math.sqrt(numOfQuestions);j++){
                    var a=document.createElement('a');
                    a.setAttribute('href','#');
                    a.setAttribute('class','qNum');
                    a.innerHTML=question;
                    a.setAttribute('id','qNum'+question);
                    a.setAttribute('onclick','goToQuestion(this)');

                    document.getElementById('qRow'+i).appendChild(a);
                    question++;
                    
                    if (numOfQuestions < question)
                        break;
                }
            }
        }
        else{
            for(var i=0;i<Math.ceil(Math.sqrt(numOfQuestions));i++){
                var div=document.createElement('div');
                div.setAttribute('class','qRow');
                div.setAttribute('id','qRow'+i);
                document.getElementById('qNumbers').appendChild(div);
                for(var j=1;j<=5;j++){
                    var a=document.createElement('a');
                    a.setAttribute('href','#');
                    a.setAttribute('class','qNum');
                    a.innerHTML=question;
                    a.setAttribute('id','qNum'+question);
                    a.setAttribute('onclick','goToQuestion(this)');

                    document.getElementById('qRow'+i).appendChild(a);
                    question++;
                    
                    if (numOfQuestions < question)
                        break;
                }
            }
        }
    }

    function goToQuestion(question){
        checked(curr);
        var x=question.innerHTML;
        document.getElementById('question'+curr).setAttribute('style','display:none');
        document.getElementById('answers'+curr).setAttribute('style','display:none');
        curr=x;
        document.getElementById('question'+curr).setAttribute('style','display:block');
        document.getElementById('answers'+curr).setAttribute('style','display:block');

        document.getElementById('prev').setAttribute('style','display:block');
        document.getElementById('next').setAttribute('style','display:block');



        if(curr==1){
            document.getElementById('prev').setAttribute('style','display:none');
        }
        if(curr!=numOfQuestions){
            document.getElementById('next').setAttribute('style','display:block');


        }
        if(curr==numOfQuestions){
            document.getElementById('next').setAttribute('style','display:none');


        }
        checked(curr);
    }

    function checked(x){

        if(document.querySelector("input[name='question"+x+"']:checked") != null) {
            document.getElementById('qNum'+x).style.backgroundColor="#b6f3e8";
        }


    }

    function checkAnswer(i,correctAnswer){
        var answer=document.querySelector('input[name="question'+i+'"]:checked').value;
        if(answer==correctAnswer){
            document.getElementById("answer"+i+"_"+answer).style.backgroundColor="rgba(48, 201, 48, 0.53)";

        }

        else{
            document.getElementById("answer"+i+"_"+answer).style.backgroundColor="rgba(204, 70, 70, 0.53)";
            document.getElementById("answer"+i+"_"+correctAnswer).style.backgroundColor="rgba(48, 201, 48, 0.53)";


        }

    }

    function showDiscussion(i){
        document.getElementById('FormExe'+i).setAttribute('style','display:none');
        document.getElementById('discussionIframe'+i).setAttribute('style','display:block');

    }

    function hideDiscussion(i){
        document.getElementById('discussionIframe'+i).setAttribute('style','display:none');
        document.getElementById('FormExe'+i).setAttribute('style','display:block');
    }
    
