import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service'; 
import { Car } from '../../models/car';
import { CarService } from '../../services/car.service'; 

@Component({
  selector: 'app-car-edit',
  templateUrl: '../car-new/car-new.component.html',
  providers: [UserService, CarService]
})
export class CarEditComponent implements OnInit {
  public page_title: string;
  public car: Car;
  public token;
  public status_car;

  constructor(
		private _route: ActivatedRoute,
		private _router: Router,
		private _userService: UserService,
		private _carService: CarService
	){
		this.token = this._userService.getToken();
	}

  ngOnInit() {
  	this._route.params.subscribe(params => {
  		let id = +params['id'];
  		this.getCar(id);
	});
  }

  getCar(id){
  		this._carService.getCar(id).subscribe(
  			response => {
  				if(response.status == 'success'){
  					this.car = response.car;
  					this.page_title = 'Editar ' + this.car.title;
  				}else{
  					this._router.navigate(['home']);
  				}
  			},
  			error => {
  				console.log(<any>error);
  			}
  		);
  }

  onSubmit(form){
  	console.log(this.car.id);
  	this._carService.update(this.token, this.car, this.car.id).subscribe(
  		response => {
  			if(response.status == 'success'){
  				this.status_car = 'success';
  				this.car = response.car;
  				this._router.navigate(['/coche', this.car.id]);
  			}else{
  				this.status_car = 'error';
  			}

		},
		error => {
			console.log(<any>error);
			this.status_car = 'error';
		}
  	);
  }

}
