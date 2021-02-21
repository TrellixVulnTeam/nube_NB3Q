import { Pettype } from './../../models/pettype';
import { PetTypesService } from './../../servicios/pet-types.service';
import { Component, OnInit, ElementRef } from '@angular/core';

@Component({
  selector: 'app-pettype-list',
  templateUrl: './pettype-list.component.html',
  styleUrls: ['./pettype-list.component.css']
})
export class PettypeListComponent implements OnInit {
  public pettypes:Pettype[];
  public is_insert: boolean = false;
  current_edit: { //para guardar una referencia de lo que se está editando ( por si cancela el usuario)
    pettype: Pettype,
    input:any
  };
  selId=-1; //para saber el id del que estamos o vamos a editar


  constructor(private servicioPetType: PetTypesService, private elRef:ElementRef) {
    this.pettypes=<Pettype[]>[],
    this.current_edit= {pettype: null, input:null};
   }

  ngOnInit(): void {
    this.servicioPetType.getPetTypes().subscribe(
      tiposanimalicos => this.pettypes = tiposanimalicos,
      error => console.log(error)
    );
  }

  showAddPettypeComponent(){
    this.is_insert = !this.is_insert;
  }

  onNewPettype(new_pettype:Pettype) {
    this.pettypes.push(new_pettype);
    this.showAddPettypeComponent();
  }

  editando(id){
    return (id!= this.selId);
  }

  editPettype(pettype: Pettype, name, nameId){
    if(this.selId==-1) {
      this.selId= nameId;
      //guardamos los valores, por si luego cancela la edición
      this.current_edit.pettype= JSON.parse(JSON.stringify(pettype));
      this.current_edit.input = name;
      //accedemos a input y le damos el foco
      this.elRef.nativeElement= name;
      this.elRef.nativeElement.focus();
    } else {
      if(pettype.id== this.current_edit.pettype.id) { //Le he dado a cancelar la edición
        this.selId=-1;
        //desacemos lo que haya podido escribir el usuario.
        pettype.name = this.current_edit.pettype.name;
      } else { //si pulsa en otro boton le volvemos a dar el foco al input en edición:
        this.elRef.nativeElement= this.current_edit.input;
        this.elRef.nativeElement.focus();
      }
    }
  }

  updatePettype(pettype:Pettype){
    console.log(pettype);
    this.servicioPetType.modPetType(pettype).subscribe(
      rs => {
        console.log(rs);
        //terminamos la edición
        this.selId=-1;
        //solo informamos si hay error:
        if (rs.result !="OK"){
          alert("ha habido un error al modificar");
        }
      }, error => console.log(error)
    );

  }

  deletePettype(pettype:Pettype){
    let msg = "¿Desea Eliminar el tipo '"+pettype.name + "' ?";
    if(confirm(msg)){
      this.servicioPetType.delPetType(pettype.id).subscribe(
        rs => {
          if(rs.result=="OK"){
            this.pettypes= this.pettypes.filter(tipo => tipo.id != pettype.id);
          } else {
            alert ("Ha habido un error al eliminar");
          }

        },
          error => console.log("error")
      );
    }
  }
}
