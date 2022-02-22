export default function InfoFilter(){
    return function(InputArray, DiscreteFilterDict, DFtype, IncludeFilterDict, IFtype) {
      
      var newArray=InputArray;
    
      for (let index = 0; index < InputArray.length; index++) {
        const item = InputArray[index];
        //DiscreteFilter
        
        for (const [key, value] of Object.entries(DiscreteFilterDict)) {
            /*if(item[key].toLowerCase()!=value.toLowerCase()){
              newArray = newArray.filter(function(value, index, arr){ 
                return value != item;
              });
            }*/

            /*if (IFtype.toLowerCase()=="and") {
                for (let index1 = 0; index1 < value.length; index1++) {
                    const ele = value[index1];
                    
                    if(item[key].toLowerCase()!=ele.toLowerCase()) ){
                        newArray = newArray.filter(function(val, index, arr){ 
                          return val != item;
                       });   
                    }
                }
                
            }*/
            
            
            if (DFtype.toLowerCase()=="or") {
                var drop=true;
                for (let index1 = 0; index1 < value.length; index1++) {
                    const ele = value[index1];
                    var a=item[key];
                    if( a == null){
                        a = "";
                    }
                    if(a.toLowerCase()==ele.toLowerCase()){
                        drop=false;
                    }
                    
                }
                //console.log(item, " ",  drop);
                if (drop && value.length!=0) {
                    newArray = newArray.filter(function(val, index, arr){ 
                          return val != item;
                    });  
                    //console.log(newArray);
                }
            }
            
        }
        //IncludeFilterDict
        for (const [key, value] of Object.entries(IncludeFilterDict)) {
            //console.log(IncludeFilterDict);
            if (IFtype.toLowerCase()=="and") {
                for (let index1 = 0; index1 < value.length; index1++) {
                    const ele = value[index1];
                    
                    if(!(item[key].toLowerCase()).includes(ele.toLowerCase()) ){
                        newArray = newArray.filter(function(val, index, arr){ 
                          return val != item;
                       });   
                    }
                }
                
            }if (IFtype.toLowerCase()=="or") {
                var drop=true;
                for (let index1 = 0; index1 < value.length; index1++) {
                    const ele = value[index1];

                    var a=item[key];
                    if( a == null){
                        a = "";
                    }
                    
                    if((a.toLowerCase()).includes(ele.toLowerCase())){
                        drop=false;

                    }
                }
                if (drop && value.length!=0) {
                    newArray = newArray.filter(function(val, index, arr){ 
                          return val != item;
                    });  
                }
            }
            /*if(!(item[key].toLowerCase()).includes(value.toLowerCase()) ){
              newArray = newArray.filter(function(value, index, arr){ 
                return value != item;
              });
            }*/ 
        }
      
      }
      return newArray;
    }
}