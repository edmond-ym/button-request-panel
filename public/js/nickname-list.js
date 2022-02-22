export default function NicknameList(){

        return function(data){
            var NicknameArray=[];
            for (let index = 0; index < data.length; index++) {
                if(!NicknameArray.includes(data[index].nickname)){
                    NicknameArray.push(data[index].nickname);
                }
            }
            return NicknameArray.sort();
        }
    
}