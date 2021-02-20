import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";
import { Vet } from "../models/vet";

@Injectable({
  providedIn: 'root'
})
export class VetService {
  private url = "http://localhost/ANGULAR/petClinic/API/petclinic/servicios.php";

  constructor(private http: HttpClient) { }

  getVets() {
    let peticion = JSON.stringify({
      accion: "ListarVets"
    });
    return this.http.post<Vet[]>(this.url, peticion);
  }

  anadeVet(mivet:Vet){
    let peticion = JSON.stringify({
      accion: "AnadeVet",
      vet: mivet
    });
    return this.http.post<Vet>(this.url, peticion);
  }

  delVet(iden:any){
    let peticion = JSON.stringify({
      accion: "BorraVet",
      id: iden
    });
    return this.http.post<Vet>(this.url, peticion);
  }

  editVet(mivet:Vet){
    let peticion = JSON.stringify({
      accion: "ModificaVet",
      vet: mivet
    });
    return this.http.post<Vet>(this.url, peticion);
  }

  getVetId(ide:any){
    let peticion = JSON.stringify({
      accion: "ObtenerVetId",
      id: ide
    });
    return this.http.post<Vet>(this.url, peticion);
  }
}
