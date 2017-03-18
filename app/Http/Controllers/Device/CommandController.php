<?php

namespace App\Http\Controllers\Device;

use App\Http\Requests\Command\Dim;
use App\Http\Requests\Command\Set;
use App\Services\IoT\IotCommandable;
use App\Services\IoT\Lightwave\Command\Dimmable;
use App\Services\IoT\Lightwave\Command\Lockable;
use App\Services\IoT\Lightwave\Command\Openable;
use App\Services\IoT\Lightwave\Command\Settable;
use App\Services\IoT\Lightwave\Command\Startable;
use App\Services\IoT\Lightwave\Command\Switchable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CommandController extends Controller
{
    /**
     * @var IotCommandable
     */
    protected $commandService;

    /**
     * @return IotCommandable
     */
    public function getCommandService()
    {
        return $this->commandService;
    }



    /**
     * CommandController constructor.
     */
    public function __construct(\EllipseSynergie\ApiResponse\Laravel\Response $response, IotCommandable $iotCommandable)
    {
        parent::__construct($response);
        $this->commandService = $iotCommandable;
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function on()
    {
        if (!($this->getCommandService() instanceof Switchable)) {
            throw new BadRequestHttpException('Device is not on-switchable');
        }

        $command = $this->getCommandService();
        $data = $command->switchOn();

        return $this->response->withArray($data);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function off()
    {
        if (!($this->getCommandService() instanceof Switchable)) {
            throw new BadRequestHttpException('Device is not off-switchable');
        }

        $command = $this->getCommandService();
        $data = $command->switchOff();

        return $this->response->withArray($data);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function lock()
    {
        if (!($this->getCommandService() instanceof Lockable)) {
            throw new BadRequestHttpException('Device is not lockable');
        }

        $command = $this->getCommandService();
        $data = $command->lock();

        return $this->response->withArray($data);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function unlock()
    {
        if (!($this->getCommandService() instanceof Lockable)) {
            throw new BadRequestHttpException('Device is not unlockable');
        }

        $command = $this->getCommandService();
        $data = $command->unlock();

        return $this->response->withArray($data);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function fullLock()
    {
        if (!($this->getCommandService() instanceof Lockable)) {
            throw new BadRequestHttpException('Device is not fully-lockable');
        }

        $command = $this->getCommandService();
        $data = $command->fullyLock();

        return $this->response->withArray($data);
    }

    /**
     * @param Request $request
     * @param Dim $dim
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function dim(Request $request, Dim $dim)
    {
        if (!($this->getCommandService() instanceof Dimmable)) {
            throw new BadRequestHttpException('Device is not dimmable');
        }

        $command = $this->getCommandService();
        $data = $command->dim($request->value);

        return $this->response->withArray($data);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function open()
    {
        if (!($this->getCommandService() instanceof Openable)) {
            throw new BadRequestHttpException('Device is not openable');
        }

        $command = $this->getCommandService();
        $data = $command->open();

        return $this->response->withArray($data);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function close()
    {
        if (!($this->getCommandService() instanceof Openable)) {
            throw new BadRequestHttpException('Device is not closeable');
        }

        $command = $this->getCommandService();
        $data = $command->close();

        return $this->response->withArray($data);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function start()
    {
        if (!($this->getCommandService() instanceof Startable)) {
            throw new BadRequestHttpException('Device is not startable');
        }

        $command = $this->getCommandService();
        $data = $command->start();

        return $this->response->withArray($data);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function stop()
    {
        if (!($this->getCommandService() instanceof Startable)) {
            throw new BadRequestHttpException('Device is not stoppable');
        }

        $command = $this->getCommandService();
        $data = $command->stop();

        return $this->response->withArray($data);
    }

    /**
     * @param Request $request
     * @param Set $set
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function set(Request $request, Set $set)
    {
        if (!($this->getCommandService() instanceof Settable)) {
            throw new BadRequestHttpException('Device is not settable');
        }

        $command = $this->getCommandService();
        $data = $command->set($request->value);

        return $this->response->withArray($data);
    }
}
