<?php

namespace App\HelperClasses;

use App\Models\BaseGeneralModel;

trait TSetupAttribute
{
    /**
     * @return void
     */
    protected function setupAttrsToStore() : void{
        if (in_array('preStoreBehaviour', $this->controllerFunctions)){
            $this->preStoreBehaviour = function ($data) {
                return $this->preStoreBehaviour($data);
            };
        }

        if (in_array('postStoreBehaviour', $this->controllerFunctions)){
            $this->postStoreBehaviour = function ($model, array $data) {
                $this->postStoreBehaviour($model, $data);
            };
        }

    }

    /**
     * @return void
     */
    protected function setupAttrsToUpdate(): void{
        if (in_array('preUpdateBehaviour', $this->controllerFunctions)) {
            $this->preUpdateBehaviour = function ($model, array $data) {
                return $this->preUpdateBehaviour($model, $data);
            };
        }

        if (in_array('postUpdateBehaviour', $this->controllerFunctions)){
            $this->postUpdateBehaviour = function ($model, $data) {
                $this->postUpdateBehaviour($model, $data);
            };
        }

    }

    /**
     * @return void
     */
    protected function setupAttrsToDelete(): void
    {
        if (in_array('preDestroyBehaviour', $this->controllerFunctions)){
            $this->preDestroyBehaviour = function ($model) {
                $this->preDestroyBehaviour($model);
            };
        }
        if (in_array('postDestroyBehaviour', $this->controllerFunctions)){
            $this->postDestroyBehaviour = function () {
                $this->postDestroyBehaviour();
            };
        }
    }
}
