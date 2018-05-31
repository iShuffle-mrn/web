var numOfQuestions=<?php echo $numOfQuestions ?>;


var curr=1;

function next(){
    document.getElementById('question'+curr).setAttribute('style','display:none');
    document.getElementById('answers'+curr).setAttribute('style','display:none');
    curr++;
    document.getElementById('question'+curr).setAttribute('style','display:block');
    document.getElementById('answers'+curr).setAttribute('style','display:block');
    document.getElementById('prev').setAttribute('style','display:block');
    if(curr == numOfQuestions){
        console.log('check');
        document.getElementById('next').setAttribute('style','display:none');
    }

}


function prev(){

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


}

function questionBar(){
        question=1;
        for(var i=0;i<Math.ceil(Math.sqrt(numOfQuestions));i++){
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
                if (question > numOfQuestions)
                    break;
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
