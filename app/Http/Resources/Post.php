<?php 
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // dd($this->domain);
        return [
          // 'name' => $this->name,
          'id' => $this->id,
          // 'events' => $this->events
        //   'domain' => $this->resource['domain'],
        //   'id' => $this->resource['id'],
        //   'is_active' => $this->resource['is_active']

        ];
    }
}
?>