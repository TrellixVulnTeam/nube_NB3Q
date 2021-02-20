import { TestBed } from '@angular/core/testing';

import { VetService } from './vet.service';

describe('VetService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: VetService = TestBed.get(VetService);
    expect(service).toBeTruthy();
  });
});
