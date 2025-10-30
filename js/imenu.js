class imenu {
 
 constructor()
 {
 }
 
 // UTILS
 //
 caption (arg)
 {
   var result = "";
  
   var firstDefinedCaption = function(obj) {
    for (var i in obj) {
     if (strlen(obj[i]) > 0) {
      return obj[i];
     }
    }
    return "";
   }
  
   if (typeof arg === "string") {
     result = arg;
   }
   else if (typeof arg === "object") {
    if (typeof globalizedLang === "function") {
      var lang = globalizedLang();
      if (val.isset(arg[lang])) {
       result = arg[lang];
       if (strlen(result) === 0) {
        result = firstDefinedCaption(arg);
       }
      }
      else {
       result = firstDefinedCaption(arg);
      }
    }
    else {
     result = firstDefinedCaption(arg);
    }
   }
  
   return result;
 }
 
 
 push(service_id, value)
 {
  if (typeof value === "undefined") { value = ""; }
  
  return new Promise(
   (resolve, reject)=>{
    
     var user_id = "";
     if (typeof fcm !== "undefined") {
       user_id = fcm.fcmkey;
     }
     else if (typeof fcmweb !== "undefined") {
       user_id = fcmweb.fcmkey;
     }
    
     var args = {
      service_id : service_id,
      user_id    : user_id,
      value      : value
     };
    
     run("imenu", args, ["imenu/imenu.php"])
     .then((res)=>{
       console.log("Resolved by run(imenu)");
			 console.log(JSON.stringify(res));
     })
     .catch(()=>{
       console.error("Rejected by run(imenu)");
       reject();
     });
   }
  );
  
  
   run("imenu")
 }
 
}

