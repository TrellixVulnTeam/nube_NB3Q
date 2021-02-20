// This file can be replaced during build by using the `fileReplacements` array.
// `ng build --prod` replaces `environment.ts` with `environment.prod.ts`.
// The list of file replacements can be found in `angular.json`.

export const environment = {
  production: false,
  url_api: "http://localhost/ANGULAR/petClinic/API/petclinic/servicios.php",

  equalJson: function (a,b){
    return JSON.stringify(a) == JSON.stringify(b);
  },

  seleccionarObj: function (lista,obj){
    var res;
    for(let i = 0; i < lista.length;i++){
      if(this.equalJson(obj, lista[i])){
        res = lista[i];
      }
    }
    return res;
  }
};

/*
 * For easier debugging in development mode, you can import the following file
 * to ignore zone related error stack frames such as `zone.run`, `zoneDelegate.invokeTask`.
 *
 * This import should be commented out in production mode because it will have a negative impact
 * on performance if an error is thrown.
 */
// import 'zone.js/dist/zone-error';  // Included with Angular CLI.
