<?php

class Store {
    public $status;
    public $cart;

    public function __construct() {
        if ( isset( $_SESSION['store'] ) ) {
            $this->status = $_SESSION['store']->status;
            $this->cart = $_SESSION['store']->cart;
        }
    }

    /**
     * Get Status
     *
     * @return (mixed)
     */
    public function getStatus() {
        session_start();
        if ( isset( $this->status ) ) {
            $_SESSION['store'] = $this;
            return $this->status;
        } else {
            return FALSE;
        }
    }

    /**
     * Set Status
     *
     * @param (string) $status
     * @return (mixed)
     */
    public function setStatus( $status=FALSE ) {
        if ( is_string( $status ) ) {
            $this->status = $status;
        } else {
            $status = FALSE;
        }
        $_SESSION['store'] = $this;
        return $status;
    }

    /**
     * Get Cart
     *
     * @return (mixed)
     */
    public function getCart() {
        if ( isset( $this->cart ) ) {
            $_SESSION['store'] = $this;
            return $this->cart;
        } else {
            return FALSE;
        }
    }

    /**
     * Set Cart
     *
     * @param (string) $status
     * @return (mixed)
     */
    public function setCart( $cart ) {
        if ( is_array( $cart ) ) {
            $this->cart = $cart;
        } else {
            $cart = FALSE;
        }
        $_SESSION['store'] = $this;
        return $cart;
    }

    public function reviewPurchase() {


    }

    public function executePurchase() {


    }

    public function receipt() {

    }
}