<?php namespace WowTables\Commands\Admin;

use WowTables\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use WowTables\Core\Repositories\Experiences\ExperiencesRepository;
use WowTables\Http\Models\Eloquent\Products\Product;

/**
 * Class RegisterUserCommand
 * @package WowTables\Commands\Site
 */
class CreateExperienceCommand extends Command implements SelfHandling {

    protected   $vendor_id ,
                $name,
                $slug,
                $status,
                $type;

    function __construct($vendor_id, $name, $slug, $status, $type)
    {
        $this->vendor_id = $vendor_id;
        $this->name = $name;
        $this->slug = $slug;
        $this->status = $status;
        $this->type = $type;
    }


    /**
     * Execute the command.
     *
     * @param RestaurantLocationsRepository|ExperiencesRepository $repository
     * @param Product $product
     * @return array
     */
    public function handle(ExperiencesRepository $repository,Product $product)
    {
        $experience = $product->add(
            $this->vendor_id,
            $this->name,
            $this->slug,
            $this->status,
            $this->type
        );

        $response = $repository->add($experience);

        //event(new NewUserWasRegistered($this->email,$this->full_name));

        return $response;
    }

}
