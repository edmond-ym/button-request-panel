export default function TimeDifferenceFilter(){
    return function(timePosted){
        var date1 = new Date(timePosted);//"2021-12-30 06:58:20"
        var date2 = new Date();
        
        var Difference_In_Seconds = (date2.getTime() - date1.getTime())/1000;
        //console.log(date1, date1.getTime(), date2, date2.getTime());
        if(Difference_In_Seconds<10){
            return("Few Seconds Ago");
        }
        if(Difference_In_Seconds>10 && Difference_In_Seconds<60){
            return("Less than a minute");
        }
        if(Difference_In_Seconds>=60 && Difference_In_Seconds<3600){
            return (Math.floor(Difference_In_Seconds/60)+" Minutes Ago")
        }
        if(Difference_In_Seconds>=3600 && Difference_In_Seconds<86400){
            return(Math.floor(Difference_In_Seconds/3600)+" Hours Ago")
        }
        if(Difference_In_Seconds>=86400 && Difference_In_Seconds<2678400){
            return(Math.floor(Difference_In_Seconds/86400)+" Days Ago")
        }
        if(Difference_In_Seconds>=2678400 && Difference_In_Seconds<32140800){
            return(Math.floor(Difference_In_Seconds/2678400)+" Months Ago")
        }
        if(Difference_In_Seconds>=32140800){
            return(Math.floor(Difference_In_Seconds/32140800)+" Years Ago")
        }
    }
}