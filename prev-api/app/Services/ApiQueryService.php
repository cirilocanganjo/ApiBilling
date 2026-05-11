<?php

namespace App\Services;
use \App\Models\{Brand, Category, City, Client, Company, Country, PaymentType, Province, Subcategory, Supply, Unit, User};
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class ApiQueryService
{
    public int $perPage;
    public function __construct(Request $request) {
        $this->perPage = filter_var(  // Quantidade por página dinâmica e se nao passado dados por (default 10)
            $request->input('per_page', 10),
            FILTER_VALIDATE_INT,
            ["options" => ["default" => 10, "min_range" => 1, "max_range" => 100]]
        );
    }

     public function GetCompanyFromService($request) {    
        $query = Company::query()->when($request->filled('name'), fn($q) => $q->where('name', 'like', "%{$request->name}%"))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('phone'), fn($q) => $q->where('phone', 'like', "%{$request->phone}%"))
            ->when($request->filled('nif'), fn($q) => $q->where('nif', $request->nif))
            ->when($request->filled('address'), fn($q) => $q->where('address', 'like', "%{$request->address}%"))
            ->when($request->filled('created_by'), fn($q) => $q->where('created_by', $request->created_by))
            ->when($request->filled('updated_by'), fn($q) => $q->where('updated_by', $request->updated_by))
            ->when($request->filled('start_date') && $request->filled('end_date'), fn($q) => $q->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date   . ' 23:59:59'
            ]));

        $companies = $query->with(['stored_by_user', 'updated_by_user'])
        ->latest()
        ->paginate($this->perPage); // Paginação dinâmica
        return $companies;   
}


    public function CreateCompany(array $data): Company
      {
        DB::beginTransaction();
        try {
            $company = Company::create($data);
            DB::commit();
            return $company;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }

     public function GetCompanyFromServiceById(string $uuid): ?Company
    {
        return Company::query()->where('uuid', $uuid)->first();
    }

        public function DeleteCompanyFromService(string $uuid, int $deletedBy): bool
    {
        DB::beginTransaction();
        try {
            $company = Company::query()->where('uuid', $uuid)->firstOrFail();           
            $company->update([
                'is_deleted' => true,
                'deleted_by' => $deletedBy
            ]);
           
            $deleted = $company->delete();
            DB::commit();
            return $deleted;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

  public function UpdateCompanyFromService(string $uuid, array $data): Company
  {
    DB::beginTransaction();
    try {
        $company = Company::query()->where('uuid', $uuid)->firstOrFail();        
        $fields = ['name', 'address', 'phone', 'nif', 'reference','status', 'updated_by']; // Lista de campos que podem ser atualizados
        $updateData = [];
       
        foreach ($fields as $field) { 
            if (array_key_exists($field, $data)) {  // Só adiciona ao array de update os campos que existem em $data
                $updateData[$field] = $data[$field];
            }
        }
       
        $company->update($updateData);       
        if (isset($data['email']) && $company->email !== $data['email']) {
            $company->update(['email' => $data['email']]);
        }

        DB::commit();
        return $company;

    } catch (\Throwable $e) {
        DB::rollBack();
        throw $e;
    }
}

public function GetUserFromService($request) {     

    /*
    ->when($request->filled('name'), fn($q) => $q->where('name', 'like', "%{$request->name}%"))
        ->when($request->filled('created_by'), fn($q) => $q->where('created_by', $request->created_by))
        ->when($request->filled('updated_by'), fn($q) => $q->where('updated_by', $request->updated_by))
        ->when($request->filled('company_name'), fn($q) => $q->whereHas('company', fn($q) => $q->where('name', 'like', "%{$request->company_name}%")))
        ->when($request->filled('company_id'), fn($q) => $q->whereHas('company', fn($q) => $q->where('id', $request->company_id)))
    */

         $query = User::query()->with(['company'])->where('company_id',auth()->user()->company_id)
         ->when($request->filled('searcher'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->searcher . '%')
                    ->orWhere('email', 'like', '%' . $request->searcher . '%');                    
                });
            })

         ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))         
         ->when($request->filled('start_date') && $request->filled('end_date'), fn($q) => $q->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date . ' 23:59:59'
        ]));
   
    $users = $query->with(['company','stored_by_user','updated_by_user'])
    ->latest()
    ->paginate($this->perPage);  // Paginação dinâmica    
    $users->getCollection()->each(fn ($user) => $user->makeHidden([ // Ocultar campos sensíveis
        'password',
        'remember_token',
        'email_verified_at',
        'deleted_at',
        'created_by',
        'updated_by',
    ]));

    return $users;
}
    
     public function CreateUser(array $data): User
    {
        DB::beginTransaction();
        try {
            $user = User::create($data);
            DB::commit();
            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }

     public function GetUserFromServiceById(string $uuid): ?User
    {
        return User::query()->with('company')->where('uuid',$uuid)->first();
    }

    public function GetUserFromServiceByPrimaryKey(int $id): ?User
    {
        return User::query()->with(['company'])->where('id', $id)->first();
    }

     public function DeleteUserFromService(string $uuid, int $deletedBy): bool
    {
        DB::beginTransaction();
        try {
            $user = User::query()->where('uuid', $uuid)->firstOrFail();           
            $user->update([
                'is_deleted' => true,
                'deleted_by' => $deletedBy
            ]);
           
            $deleted = $user->delete();
            DB::commit();
            return $deleted > 0;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

     public function UpdateUserFromService(string $uuid, array $data): User
    {
        DB::beginTransaction();
        try {
        $user = User::query()->where('uuid', $uuid)->firstOrFail();
        $fields = ['name', 'password', 'company_id', 'type', 'updated_by'];
        $updateData = [];

        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }
        
        $user->update($updateData);
            
        if (isset($data['email']) && $user->email !== $data['email']) { // Atualiza email separadamente se mudou
            $user->update(['email' => $data['email']]);
        }

            DB::commit();
            return $user;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }

     public function GetClientFromService($request) {        
      
      /*
      ->when($request->filled('name'), fn($q) => $q->where('name', 'like', "%{$request->name}%"))
       Codigo comentado, usava-se antes filtros individuais, agora usa-se o filtro unico 'searcher'

      ->when($request->filled('phone'), fn($q) => $q->where('phone', 'like', "%{$request->phone}%"))
        ->when($request->filled('address'), fn($q) => $q->where('address', 'like', "%{$request->address}%"))
        ->when($request->filled('created_by'), fn($q) => $q->where('created_by', $request->created_by))
        ->when($request->filled('updated_by'), fn($q) => $q->where('updated_by', $request->updated_by))
        ->when($request->filled('company_name'), fn($q) => $q->whereHas('company', fn($q) => $q->where('name', 'like', "%{$request->company_name}%")))
        ->when($request->filled('company_id'), fn($q) => $q->whereHas('company', fn($q) => $q->where('id', $request->company_id)))
        */

        $query = Client::query()->where('company_id',auth()->user()->company_id)
        ->when($request->filled('searcher'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->searcher . '%')
                    ->orWhere('phone', 'like', '%' . $request->searcher . '%')
                    ->orWhere('address', 'like', '%' . $request->searcher . '%')
                    ->orWhere('neighborhood', 'like', '%' . $request->searcher . '%')
                    ->orWhere('email', 'like', '%' . $request->searcher . '%')
                    ->orWhere('tax_id', $request->searcher);
                });
            })


        ->when($request->filled('start_date') && $request->filled('end_date'), fn($q) => $q->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date   . ' 23:59:59'
        ]));

        $client = $query->with(['company', 'city','province', 'country', 'stored_by_user','updated_by_user'])
        ->latest()
        ->paginate($this->perPage); 
        return $client;   
}

 public function CreateClient(array $data): Client
      {
        DB::beginTransaction();
        try {
            $client = Client::create($data);
            DB::commit();
            return $client;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }

     public function GetClientFromServiceById(string $uuid): ?Client
    {
        return Client::query()->with(['company', 'city','province', 'country', 'stored_by_user','updated_by_user'])->where('uuid', $uuid)->first();
    }

    public function GetClientFromServiceByPrimaryKey(int $id): ?Client
    {
        return Client::query()->with(['company', 'city','province', 'country', 'stored_by_user','updated_by_user'])->where('id', $id)->first();
    }

     public function DeleteClientFromService(string $uuid, int $deletedBy): bool
    {
        DB::beginTransaction();
        try {
            $client = Client::query()->where('uuid', $uuid)->firstOrFail();           
            $client->update([
                'is_deleted' => true,
                'deleted_by' => $deletedBy
            ]);
           
            $deleted = $client->delete();
            DB::commit();
            return $deleted;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

     public function UpdateClientFromService(string $uuid, array $data): Client
    {
        DB::beginTransaction();
        try {
        $client = Client::query()->where('uuid', $uuid)->firstOrFail();    

        $fields = [
            'name', 
            'company_id',
            'tax_id',  
            'country_id',
            'province_id',
            'address',
            'city_id',
            'complement',
            'neighborhood',
            'postal_code',
            'recipient',
            'notes',
            'email',
            'phone',
            'updated_by'
        ];       

        $updateData = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }
        
        $client->update($updateData);         
        
            DB::commit();
            return $client;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }


     public function GetBrandFromService($request) {    
      $query = Brand::query()->where('company_id',auth()->user()->company_id)
      ->when($request->filled('name'), fn($q) => $q->where('name', 'like', "%{$request->name}%"))
        ->when($request->filled('company_name'), fn($q) => $q->whereHas('company', fn($q) => $q->where('name', 'like', "%{$request->company_name}%")))
        ->when($request->filled('company_id'), fn($q) => $q->whereHas('company', fn($q) => $q->where('id', $request->company_id)))
        ->when($request->filled('created_by'), fn($q) => $q->where('created_by', $request->created_by))
        ->when($request->filled('updated_by'), fn($q) => $q->where('updated_by', $request->updated_by))
        ->when($request->filled('start_date') && $request->filled('end_date'), fn($q) => $q->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date   . ' 23:59:59'
        ]));

        $brand = $query->with(['company', 'stored_by_user','updated_by_user'])
        ->latest()
        ->paginate($this->perPage); 
        return $brand;   
}

 public function CreateBrand(array $data): Brand
      {
        DB::beginTransaction();
        try {
            $brand = Brand::create($data);
            DB::commit();
            return $brand;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }

     public function GetBrandFromServiceById(string $uuid): ?Brand
    {
        return Brand::query()->with('company')->where('uuid', $uuid)->first();
    }

     public function DeleteBrandFromService(string $uuid, int $deletedBy): bool
    {
        DB::beginTransaction();
        try {
            $brand = Brand::query()->where('uuid', $uuid)->firstOrFail();           
            $brand->update([
                'is_deleted' => true,
                'deleted_by' => $deletedBy
            ]);
           
            $deleted = $brand->delete();
            DB::commit();
            return $deleted;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

     public function UpdateBrandFromService(string $uuid, array $data): Brand
    {
        DB::beginTransaction();
        try {
        $brand = Brand::query()->where('uuid', $uuid)->firstOrFail();
        $fields = ['name', 'company_id', 'updated_by'];       
        $updateData = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }
        
        $brand->update($updateData);         
        
            DB::commit();
            return $brand;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }


     public function GetUnitFromService($request) {        
      
      /*
      ->when($request->filled('name'), fn($q) => $q->where('name', 'like', "%{$request->name}%"))
      ->when($request->filled('acronym'), fn($q) => $q->where('acronym', 'like', "%{$request->acronym}%"))
        ->when($request->filled('company_name'), fn($q) => $q->whereHas('company', fn($q) => $q->where('name', 'like', "%{$request->company_name}%")))
        ->when($request->filled('company_id'), fn($q) => $q->whereHas('company', fn($q) => $q->where('id', $request->company_id)))
        ->when($request->filled('created_by'), fn($q) => $q->where('created_by', $request->created_by))
        ->when($request->filled('updated_by'), fn($q) => $q->where('updated_by', $request->updated_by))
        */

        $query = Unit::query()->where('company_id',auth()->user()->company_id)
        ->when($request->filled('searcher'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->searcher . '%')
                    ->orWhere('acronym', 'like', '%' . $request->searcher . '%');                  
                });
        })

        ->when($request->filled('start_date') && $request->filled('end_date'), fn($q) => $q->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date   . ' 23:59:59'
        ]));

        $unit = $query->with(['company', 'stored_by_user','updated_by_user'])
        ->latest()
        ->paginate($this->perPage); 
        return $unit;   
}

 public function CreateUnit(array $data): Unit
      {
        DB::beginTransaction();
        try {
            $unit = Unit::create($data);
            DB::commit();
            return $unit;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }

     public function GetUnitFromServiceById(string $uuid): ?Unit
    {
        return Unit::query()->with('company')->where('uuid', $uuid)->first();
    }

     public function DeleteUnitFromService(string $uuid, int $deletedBy): bool
    {
        DB::beginTransaction();
        try {
            $unit = Unit::query()->where('uuid', $uuid)->firstOrFail();           
            $unit->update([
                'is_deleted' => true,
                'deleted_by' => $deletedBy
            ]);
           
            $deleted = $unit->delete();
            DB::commit();
            return $deleted;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

     public function UpdateUnitFromService(string $uuid, array $data): Unit
    {
        DB::beginTransaction();
        try {
        $unit = Unit::query()->where('uuid', $uuid)->firstOrFail();
        $fields = ['name', 'acronym', 'company_id', 'updated_by'];       
        $updateData = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }
        
        $unit->update($updateData);         
        
            DB::commit();
            return $unit;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }

     public function GetCategoryFromService($request) {    
     
      /*
      ->when($request->filled('name'), fn($q) => $q->where('name', 'like', "%{$request->name}%"))
      ->when($request->filled('description'),  fn($q) => $q->where('description', 'like', "%{$request->description}%"))
        ->when($request->filled('company_name'), fn($q) => $q->whereHas('company', fn($q) => $q->where('name', 'like', "%{$request->company_name}%")))
        ->when($request->filled('company_id'), fn($q) => $q->whereHas('company', fn($q) => $q->where('id', $request->company_id)))
        ->when($request->filled('created_by'), fn($q) => $q->where('created_by', $request->created_by))
        ->when($request->filled('updated_by'), fn($q) => $q->where('updated_by', $request->updated_by))
        */

         $query = Category::query()->where('company_id',auth()->user()->company_id)
         ->when($request->filled('searcher'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->searcher . '%')
                    ->orWhere('description', 'like', '%' . $request->searcher . '%');                  
                });
            })

        ->when($request->filled('start_date') && $request->filled('end_date'), fn($q) => $q->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date   . ' 23:59:59'
        ]));

        $category = $query->with(['company', 'stored_by_user','updated_by_user'])
        ->latest()
        ->paginate($this->perPage);       
        return $category;            
}

 public function CreateCategory(array $data): Category
      {
        DB::beginTransaction();
        try {
            $category = Category::create($data);
            DB::commit();
            return $category;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }

     public function GetCategoryFromServiceById(string $uuid): ?Category
    {
        return Category::query()->with(['company'])->where('uuid', $uuid)->first();
    }

     public function DeleteCategoryFromService(string $uuid, int $deletedBy): bool
{
    DB::beginTransaction();
    try {       
        $category = Category::query()->where('uuid', $uuid)->firstOrFail();        
        $category->update([
            'is_deleted' => true,
            'deleted_by' => $deletedBy,
        ]);
       
        $deleted = $category->delete();
        DB::commit();
        return $deleted; 

    } catch (\Throwable $e) {
        DB::rollBack();        
        throw $e;
    }
    }

     public function UpdateCategoryFromService(string $uuid, array $data): Category
    {
        DB::beginTransaction();
        try {
        $category = Category::query()->where('uuid', $uuid)->firstOrFail();
        $fields = ['name', 'description', 'company_id', 'updated_by'];       
        $updateData = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }
        
        $category->update($updateData);         
        
            DB::commit();
            return $category;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }

     public function GetSubCategoryFromService($request) {    
      
      /*
      ->when($request->filled('name'), fn($q) => $q->where('name', 'like', "%{$request->name}%"))
      ->when($request->filled('category_id'), fn($q) => $q->whereHas('category', fn($q) => $q->where('id', $request->category_id)))
        ->when($request->filled('company_id'), fn($q) => $q->whereHas('category', fn($q) => $q->where('company_id', $request->company_id)))
        ->when($request->filled('created_by'), fn($q) => $q->where('created_by', $request->created_by))
        ->when($request->filled('updated_by'), fn($q) => $q->where('updated_by', $request->updated_by))
        */

         $query = Subcategory::query()->where('company_id',auth()->user()->company_id)
         ->when($request->filled('searcher'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->searcher . '%')
                    ->orWhereRelation('category','name', 'like', '%' . $request->searcher . '%');                  
                });
            })

        ->when($request->filled('start_date') && $request->filled('end_date'), fn($q) => $q->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date   . ' 23:59:59'
        ]));

        $subCategory = $query->with(['category.company','stored_by_user','updated_by_user'])
        ->whereHas('category', function ($q) {
            $q->where('company_id',auth()->user()->company_id);
        })
        ->latest()
        ->paginate($this->perPage);    
        return $subCategory;   
}

 public function CreateSubCategory(array $data): Subcategory
      {
        DB::beginTransaction();
        try {
            $brand = Subcategory::create($data);
            DB::commit();
            return $brand;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }

     public function GetSubCategoryFromServiceById(string $uuid): ?Subcategory
    {
        return Subcategory::query()->with('category')->where('uuid', $uuid)->first();
    }

     public function DeleteSubCategoryFromService(string $uuid, int $deletedBy): bool
    {
        DB::beginTransaction();
        try {
            $subCategory = Subcategory::query()->where('uuid', $uuid)->firstOrFail();           
            $subCategory->update([
                'is_deleted' => true,
                'deleted_by' => $deletedBy
            ]);
           
            $deleted = $subCategory->delete();
            DB::commit();
            return $deleted;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

     public function UpdateSubCategoryFromService(string $uuid, array $data): Subcategory
    {
        DB::beginTransaction();
        try {
        $subCategory = Subcategory::query()->where('uuid', $uuid)->firstOrFail();
        $fields = ['name', 'category_id', 'updated_by'];       
        $updateData = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }
        
        $subCategory->update($updateData);         
        
            DB::commit();
            return $subCategory;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }


    public function GetSupplyFromService($request) {        
      
      /*
      ->when($request->filled('name'), fn($q) => $q->where('name', 'like', "%{$request->name}%"))
      ->when($request->filled('city_id'), fn($q) => $q->where('city_id', $request->city_id))
        ->when($request->filled('country_id'), fn($q) => $q->where('country_id', $request->country_id))
        ->when($request->filled('province_id'), fn($q) => $q->where('province_id', $request->province_id))
        ->when($request->filled('address'), fn($q) => $q->where('address', 'like',"%{$request->address}%"))
        ->when($request->filled('neighborhood'), fn($q) => $q->where('neighborhood', 'like',"%{$request->neighborhood}%"))
        ->when($request->filled('company_id'), fn($q) => $q->where('company_id', $request->company_id))
        ->when($request->filled('postal_code'), fn($q) => $q->where('postal_code', $request->postal_code))
        ->when($request->filled('created_by'), fn($q) => $q->where('created_by', $request->created_by))
        ->when($request->filled('updated_by'), fn($q) => $q->where('updated_by', $request->updated_by))
       */

          $query = Supply::query()->where('company_id',auth()->user()->company_id)
          ->when($request->filled('searcher'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->searcher . '%')
                    ->orWhere('phone', 'like', '%' . $request->searcher . '%')
                    ->orWhere('address', 'like', '%' . $request->searcher . '%')
                    ->orWhere('neighborhood', 'like', '%' . $request->searcher . '%')
                    ->orWhere('email', 'like', '%' . $request->searcher . '%')
                    ->orWhere('tax_id', $request->searcher);
                });
            })
       
        ->when($request->filled('start_date') && $request->filled('end_date'), fn($q) => $q->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date   . ' 23:59:59'
        ]));

        $suppliers = $query->with(['company','country','province', 'city' , 'stored_by_user','updated_by_user'])
        ->latest()
        ->paginate($this->perPage);    
        return $suppliers;   
}

 public function CreateSupply(array $data): Supply
      {
        DB::beginTransaction();
        try {
            $supply = Supply::create($data);
            DB::commit();
            return $supply;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }

     public function GetSupplyFromServiceById(string $uuid): ?Supply
    {
        return Supply::query()->with(['country','province', 'city' , 'stored_by_user','updated_by_user'])->where('uuid', $uuid)->first();
    }

      public function GetSupplierFromServiceByPrimaryKey(int $id): ?Supply
    {
        return Supply::query()->with(['country','province', 'city' , 'stored_by_user','updated_by_user'])->where('id', $id)->first();
    }

     public function DeleteSupplyFromService(string $uuid, int $deletedBy): bool
    {
        DB::beginTransaction();
        try {
            $supply = Supply::query()->where('uuid', $uuid)->firstOrFail();           
            $supply->update([
                'is_deleted' => true,
                'deleted_by' => $deletedBy
            ]);
           
            $deleted = $supply->delete();
            DB::commit();
            return $deleted;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

     public function UpdateSupplyFromService(string $uuid, array $data): Supply
    {
        DB::beginTransaction();
        try {
        $supply = Supply::query()->with(['country','province', 'city' , 'stored_by_user','updated_by_user'])->where('uuid', $uuid)->firstOrFail();
        $fields = [
            'name', 
            'updated_by',
            'company_id',
            'natural_person',
            'tax_id',
            'country_id',
            'province_id',
            'city_id',
            'address',
            'complement',
            'neighborhood',
            'postal_code',
            'contact_person',
            'notes',
            'phone',
            'email',
        ];       
        $updateData = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }
        
        $supply->update($updateData);         
        
            DB::commit();
            return $supply;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }


    public function GetCitiesFromService($request) {    
      $query = City::query()->when($request->filled('name'), fn($q) => $q->where('name', 'like', "%{$request->name}%"))
        ->when($request->filled('province_id'), fn($q) => $q->where('province_id', $request->province_id))
        ->when($request->filled('created_by'), fn($q) => $q->where('created_by', $request->created_by))
        ->when($request->filled('updated_by'), fn($q) => $q->where('updated_by', $request->updated_by))
        ->when($request->filled('start_date') && $request->filled('end_date'), fn($q) => $q->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date   . ' 23:59:59'
        ]));

        $cities = $query->with(['province', 'stored_by_user','updated_by_user'])
        ->latest()
        ->paginate($this->perPage);    
        return $cities;   
}

 public function CreateCity(array $data): City
      {
        DB::beginTransaction();
        try {
            $city = City::create($data);
            DB::commit();
            return $city;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }

     public function GetCityFromServiceById(string $uuid): ?City
    {
        return City::query()->with(['province'])->where('uuid', $uuid)->first();
    }

    public function DeleteCityFromService(string $uuid, int $deletedBy): bool
    {
        DB::beginTransaction();
        try {
            $city = City::query()->where('uuid', $uuid)->firstOrFail();           
            $city->update([
                'is_deleted' => true,
                'deleted_by' => $deletedBy
            ]);
           
            $deleted = $city->delete();
            DB::commit();
            return $deleted;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

     public function UpdateCityFromService(string $uuid, array $data): City
    {
        DB::beginTransaction();
        try {
        $city = City::query()->with(['province'])->where('uuid', $uuid)->firstOrFail();
        $fields = [
            'name', 
            'province_id',
            'updated_by'
        ];       
        $updateData = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }
        
        $city->update($updateData);         
        
            DB::commit();
            return $city;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }


  public function GetCountriesFromService($request) {    
      $query = Country::query()->when($request->filled('name'), fn($q) => $q->where('name', 'like', "%{$request->name}%"))
        ->when($request->filled('created_by'), fn($q) => $q->where('created_by', $request->created_by))
        ->when($request->filled('updated_by'), fn($q) => $q->where('updated_by', $request->updated_by))
        ->when($request->filled('start_date') && $request->filled('end_date'), fn($q) => $q->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date   . ' 23:59:59'
        ]));

        $countries = $query->with(['stored_by_user','updated_by_user'])
        ->latest()
        ->paginate($this->perPage);    
        return $countries;   
}

 public function CreateCountry(array $data): Country
      {
        DB::beginTransaction();
        try {
            $city = Country::create($data);
            DB::commit();
            return $city;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }

     public function GetCountryFromServiceById(int $id): ?Country
    {
        return Country::query()->find($id);
    }

    public function DeleteCountryFromService(int $id, int $deletedBy): bool
    {
        DB::beginTransaction();
        try {
            $country = Country::query()->findOrFail($id);           
            $country->update([
                'is_deleted' => true,
                'deleted_by' => $deletedBy
            ]);
           
            $deleted = Country::destroy([$country->id]);
            DB::commit();
            return $deleted > 0;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

     public function UpdateCountryFromService(int $id, array $data): Country
    {
        DB::beginTransaction();
        try {
        $country = Country::query()->with(['province'])->findOrFail($id);
        $fields = [
            'name', 
            'updated_by'
        ];       
        $updateData = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }
        
        $country->update($updateData);         
        
            DB::commit();
            return $country;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }


    public function GetProvincesFromService($request) {    
      $query = Province::query()->where('company_id',auth()->user()->company_id)
      ->when($request->filled('name'), fn($q) => $q->where('name', 'like', "%{$request->name}%"))
        ->when($request->filled('country_id'), fn($q) => $q->where('country_id', $request->country_id))
        ->when($request->filled('created_by'), fn($q) => $q->where('created_by', $request->created_by))
        ->when($request->filled('updated_by'), fn($q) => $q->where('updated_by', $request->updated_by))
        ->when($request->filled('start_date') && $request->filled('end_date'), fn($q) => $q->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date   . ' 23:59:59'
        ]));

        $provinces = $query->with(['country', 'stored_by_user','updated_by_user'])
        ->latest()
        ->paginate($this->perPage);    
        return $provinces;   
}

 public function CreateProvince(array $data): Province
      {
        DB::beginTransaction();
        try {
            $province = Province::create($data);
            DB::commit();
            return $province;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }

     public function GetProvinceFromServiceById(int $id): ?Province
    {
        return Province::query()->with(['country'])->find($id);
    }

    public function DeleteProvinceFromService(int $id, int $deletedBy): bool
    {
        DB::beginTransaction();
        try {
            $province = Province::query()->findOrFail($id);           
            $province->update([
                'is_deleted' => true,
                'deleted_by' => $deletedBy
            ]);
           
            $deleted = Province::destroy([$province->id]);
            DB::commit();
            return $deleted > 0;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

     public function UpdateProvinceFromService(int $id, array $data): Province
    {
        DB::beginTransaction();
        try {
        $province = Province::query()->with(['province'])->findOrFail($id);
        $fields = [
            'name', 
            'province_id',
            'updated_by'
        ];       
        $updateData = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }
        
        $province->update($updateData);         
        
            DB::commit();
            return $province;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }


    public function GetPaymentTypeFromService($request) {    

         $query = PaymentType::query()->when($request->filled('searcher'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->searcher . '%')
                    ->orWhere('description', 'like', '%' . $request->searcher . '%');                  
                });
            })

        ->when($request->filled('start_date') && $request->filled('end_date'), fn($q) => $q->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date   . ' 23:59:59'
        ]));

        $payment_types = $query->with(['user'])
        ->latest()
        ->paginate($this->perPage);       
        return $payment_types;            
}

public function CreatePaymentType(array $data): PaymentType
      {
        DB::beginTransaction();
        try {
            $payment_type = PaymentType::create($data);
            DB::commit();
            return $payment_type;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }
    }

    public function RenamUserPasswordFromService (string $uuid, array $data) : User
    {
        DB::beginTransaction();
        try {
        $user = User::query()->where('uuid', $uuid)->firstOrFail();
        $fields = [
            'password', 
        ];       
        $updateData = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }
        
        $user->update($updateData);         
        
            DB::commit();
            return $user;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; 
        }

    }

    public function GetUserReportFromService($request)
{
    if (!$request->filled('searcher') && !$request->has('active_user_id')  ) {   
        return collect();
    }

    return Supply::query()
        ->where('company_id', auth()->user()->company_id)

        // Pesquisa por texto
        ->when($request->filled('searcher'), function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->searcher}%")
                  ->orWhere('email', $request->searcher)
                  ->orWhere('phone', $request->searcher);
            });
        })

        // Filtro por utilizador (SÓ quando for um ID real)
        ->when($request->filled('active_user_id'), function ($query) use ($request) {
            $query->whereHas('stored_by_user', function ($q) use ($request) {
                $q->where('status', 'active')
                  ->where('company_id', auth()->user()->company_id)
                  ->where('id', $request->active_user_id);
            });
        })

        ->with('stored_by_user')
        ->latest()
        ->get();
}


   
    
    
}