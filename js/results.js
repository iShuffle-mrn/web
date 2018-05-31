        var curr=1;
        var question;
        function next(){
            
            document.getElementById('question'+curr).setAttribute('style','display:none');
            document.getElementById('answers'+curr).setAttribute('style','display:none');
            curr++;
            document.getElementById('question'+curr).setAttribute('style','display:block');
            document.getElementById('answers'+curr).setAttribute('style','display:block');
            document.getElementById('prev').setAttribute('style','display:block');
            if(curr==numOfQuestions){
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

    

                    
        
        function goToQuestion(question){
            
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
            
        }
        
