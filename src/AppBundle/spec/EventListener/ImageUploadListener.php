<?php

namespace spec\AppBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class ImageUploadListenerSpec extends ObjectBehavior
{
    function let(ImageUploaderInterface $imageUploader)
    {
        $this->beConstructedWith($imageUploader);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\EventListener\ImageUploadListener');
    }

    function it_uploads_image_if_file_is_set(
        ImageUploaderInterface $imageUploader,
        GenericEvent $event,
        ImageInterface $image
    ) {
        $event->getSubject()->willReturn($image);

        $image->hasFile()->willReturn(true);
        $imageUploader->upload($image)->shouldBeCalled();

        $this->uploadImage($event);
    }

    function it_does_nothing_if_file_is_not_set(
        ImageUploaderInterface $imageUploader,
        GenericEvent $event,
        ImageInterface $image
    ) {
        $event->getSubject()->willReturn($image);

        $image->hasFile()->willReturn(false);
        $imageUploader->upload($image)->shouldNotBeCalled();

        $this->uploadImage($event);
    }

    function it_throws_exception_if_other_object_than_image_is_passed(GenericEvent $event) {
        $event->getSubject()->willReturn(new \stdClass());

        $this->shouldThrow(\InvalidArgumentException::class)->during('uploadImage', [$event]);
    }
}
